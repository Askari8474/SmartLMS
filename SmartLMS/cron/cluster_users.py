import mysql.connector
import pandas as pd
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score
import datetime
import sys

# Database connection
config = {
    'user': 'root',
    'password': '',
    'host': 'localhost',
    'database': 'smart_lms',
}

def cluster_users():
    try:
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor()

        # Fetch engagement data
        # student_id is the column name in engagement_scores
        query = "SELECT student_id as user_id, login_frequency, avg_time_spent, avg_quiz_score, course_completion_rate FROM engagement_scores"
        df = pd.read_sql(query, conn)

        if df.empty:
            print("No data found in engagement_scores.")
            conn.close()
            return

        # Prepare feature matrix
        features = ['login_frequency', 'avg_time_spent', 'avg_quiz_score', 'course_completion_rate']
        X = df[features].fillna(0)

        # Check if we have enough samples and distinct points for clustering
        n_samples = len(X)
        # Drop duplicates to see how many unique points we have
        n_unique_samples = len(X.drop_duplicates())
        
        if n_samples < 3 or n_unique_samples < 3:
            n_clusters = min(n_samples, n_unique_samples, 3)
        else:
            n_clusters = 3

        if n_clusters > 1:
            kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
            clusters = kmeans.fit_predict(X)
            
            # Check if we actually got more than one cluster label
            unique_labels = len(set(clusters))
            if unique_labels > 1:
                sil_score = silhouette_score(X, clusters)
            else:
                sil_score = 0.0
        else:
            clusters = [0] * n_samples
            sil_score = 0.0

        print(f"Silhouette Score: {sil_score}")

        # Store clusters back to DB
        calculated_at = datetime.datetime.now()
        for idx, row in df.iterrows():
            user_id = int(row['user_id'])
            cluster_id = int(clusters[idx])
            
            replace_query = """
            REPLACE INTO user_clusters (user_id, cluster_id, silhouette_score, calculated_at) 
            VALUES (%s, %s, %s, %s)
            """
            cursor.execute(replace_query, (user_id, cluster_id, float(sil_score), calculated_at))

        conn.commit()
        
        # Log global silhouette score
        log_query = "INSERT INTO cluster_log (silhouette_score, calculated_at) VALUES (%s, %s)"
        cursor.execute(log_query, (float(sil_score), calculated_at))
        conn.commit()
        
        print(f"User clusters updated successfully for {n_samples} users.")

    except mysql.connector.Error as err:
        print(f"Database Error: {err}")
    except Exception as e:
        print(f"Unexpected Error: {e}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()

if __name__ == "__main__":
    cluster_users()
