import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import datetime
import sys

# Database connection
config = {
    'user': 'root',
    'password': '',
    'host': 'localhost',
    'database': 'smart_lms',
}

def generate_recommendations():
    try:
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor()

        # 1. Fetch all resources with their tags
        resources = pd.read_sql("SELECT id, tags FROM resources", conn)
        if resources.empty:
            print("No resources found.")
            return

        # 2. Build tag vocabulary and matrix using TF-IDF
        # This makes the engine much more precise
        vec = TfidfVectorizer(token_pattern='[^,]+')
        tag_matrix = vec.fit_transform(resources['tags'].fillna('')).toarray()
        resource_ids = resources['id'].tolist()

        # 3. Fetch all learners
        users = pd.read_sql("SELECT id FROM users WHERE role='learner'", conn)
        if users.empty:
            print("No learners found.")
            return

        print(f"Generating high-accuracy recommendations for {len(users)} users...")
        generated_at = datetime.datetime.now()

        # Clear old recommendations before generating new ones
        cursor.execute("DELETE FROM recommendations")
        conn.commit()

        for user in users.itertuples():
            # 4. Get viewed resource IDs for this student
            viewed_query = "SELECT resource_id FROM resource_views WHERE student_id = %s"
            viewed = pd.read_sql(viewed_query, conn, params=(user.id,))
            
            if viewed.empty:
                continue
            
            viewed_ids = viewed['resource_id'].unique().tolist()
            
            try:
                # Find indices of viewed resources
                viewed_indices = [resource_ids.index(rid) for rid in viewed_ids if rid in resource_ids]
                if not viewed_indices:
                    continue
                
                # 5. Build user profile (weighted mean of viewed resources)
                profile = tag_matrix[viewed_indices].mean(axis=0)
                
                # 6. Compute cosine similarity
                sim = cosine_similarity([profile], tag_matrix).flatten()
                
                # 7. Mask already viewed resources
                for rid in viewed_ids:
                    if rid in resource_ids:
                        sim[resource_ids.index(rid)] = -1
                
                # 8. Get top N
                top_n = 10
                top_indices = sim.argsort()[-top_n:][::-1]
                
                # 9. Store recommendations (using a strict threshold of 0.1)
                for rank, idx in enumerate(top_indices):
                    if sim[idx] >= 0.1: 
                        replace_query = """
                        INSERT INTO recommendations (user_id, resource_id, score, rank, generated_at) 
                        VALUES (%s, %s, %s, %s, %s)
                        """
                        cursor.execute(replace_query, (user.id, int(resource_ids[idx]), float(sim[idx]), rank + 1, generated_at))
                
                conn.commit()
            except Exception as e:
                print(f"Error processing user {user.id}: {e}")
                continue

        print("Precision-based recommendations updated successfully.")

    except mysql.connector.Error as err:
        print(f"Database Error: {err}")
    except Exception as e:
        print(f"Unexpected Error: {e}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()

if __name__ == "__main__":
    generate_recommendations()
