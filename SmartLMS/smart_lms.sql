-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2026 at 12:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cluster_log`
--

CREATE TABLE `cluster_log` (
  `id` int(11) NOT NULL,
  `silhouette_score` decimal(10,5) NOT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cluster_log`
--

INSERT INTO `cluster_log` (`id`, `silhouette_score`, `calculated_at`) VALUES
(1, 0.66667, '2026-04-07 22:28:46'),
(2, 0.66667, '2026-04-14 21:34:19'),
(3, 0.66667, '2026-04-14 21:56:16'),
(4, 0.83333, '2026-04-14 22:09:00'),
(5, 0.71190, '2026-04-14 22:15:16'),
(6, 0.55694, '2026-04-14 22:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `instructor_id`, `created_at`, `tags`) VALUES
(2, 'Web Development 101', 'Learn the basics of HTML, CSS, and JavaScript.', 3, '2026-04-07 22:25:09', 'web,frontend,html,css,javascript'),
(3, 'Advanced PHP & MySQL', 'Deep dive into server-side programming and databases.', 3, '2026-04-07 22:25:09', 'php,mysql,backend,server,web'),
(4, 'Python for Data Science', 'Introduction to NumPy, Pandas, and Matplotlib.', 4, '2026-04-07 22:25:09', 'python,data-science,numpy,pandas,analysis'),
(5, 'Introduction to AI', 'Overview of Artificial Intelligence and Machine Learning.', 4, '2026-04-07 22:25:09', 'ai,machine-learning,theory,intelligence'),
(6, 'Graphic Design Basics', 'Principles of design using Adobe Illustrator and Photoshop.', 5, '2026-04-07 22:25:09', 'design,graphic,creative,adobe,branding'),
(7, 'Cybersecurity Essentials', 'Learn how to protect systems and networks.', 5, '2026-04-07 22:25:09', 'cybersecurity,network,security,protection'),
(8, 'Cloud Computing with AWS', 'Introduction to Amazon Web Services and cloud architecture.', 3, '2026-04-07 22:25:09', 'cloud,aws,infrastructure,devops'),
(9, 'Mobile App Development with Flutter', 'Build cross-platform mobile apps.', 4, '2026-04-07 22:25:09', 'mobile,flutter,dart,app,cross-platform'),
(10, 'Digital Marketing 101', 'Learn SEO, SEM, and social media marketing.', 5, '2026-04-07 22:25:09', 'marketing,seo,digital,strategy,growth'),
(11, 'Blockchain Technology Fundamentals', 'Understand how blockchain works and its applications.', 3, '2026-04-07 22:25:09', 'blockchain,crypto,ledger,web3,decentralized'),
(12, 'Modern JavaScript Patterns', 'Explore advanced design patterns and best practices in modern JS.', 3, '2026-04-07 22:25:09', 'javascript,patterns,advanced,js,web'),
(13, 'Node.js Microservices', 'Learn how to build and scale microservices with Node.js.', 3, '2026-04-07 22:25:09', 'node,microservices,backend,api,scalability'),
(14, 'React State Management', 'Master Redux, Context API, and Zustand for state management.', 3, '2026-04-07 22:25:09', 'react,frontend,state,redux,web'),
(15, 'Docker for Developers', 'Containerize your applications and streamline your workflow.', 3, '2026-04-07 22:25:09', 'docker,container,devops,deployment,linux'),
(16, 'GraphQL API Design', 'Build efficient and flexible APIs with GraphQL.', 3, '2026-04-07 22:25:09', 'graphql,api,backend,query,web'),
(17, 'Machine Learning with Scikit-Learn', 'Practical introduction to machine learning using Python.', 4, '2026-04-07 22:25:09', 'ml,scikit-learn,python,data,prediction'),
(18, 'Deep Learning with TensorFlow', 'Build neural networks and deep learning models.', 4, '2026-04-07 22:25:09', 'deep-learning,tensorflow,neural-networks,ai,python'),
(19, 'Natural Language Processing', 'Learn how to process and analyze text data with Python.', 4, '2026-04-07 22:25:09', 'nlp,text-analysis,ai,python,language'),
(20, 'Data Visualization with D3.js', 'Create interactive and dynamic data visualizations.', 4, '2026-04-07 22:25:09', 'd3,visualization,data,frontend,javascript'),
(21, 'Statistics for Data Science', 'Essential statistical concepts for data analysis.', 4, '2026-04-07 22:25:09', 'statistics,math,data-science,analysis,python'),
(22, 'UI/UX Design Principles', 'Master the fundamentals of user interface and experience design.', 5, '2026-04-07 22:25:09', 'ui,ux,design,prototype,user-experience'),
(23, 'Social Media Strategy', 'Develop effective strategies for social media growth.', 5, '2026-04-07 22:25:09', 'social-media,strategy,marketing,growth,engagement'),
(24, 'Content Marketing Mastery', 'Learn how to create compelling content that converts.', 5, '2026-04-07 22:25:09', 'content,marketing,writing,strategy,conversion'),
(25, 'SEO Optimization Techniques', 'Improve your website ranking with advanced SEO.', 5, '2026-04-07 22:25:09', 'seo,ranking,search,marketing,web'),
(26, 'Brand Identity Design', 'Create memorable brand identities for businesses.', 5, '2026-04-07 22:25:09', 'branding,design,logo,identity,creative');

-- --------------------------------------------------------

--
-- Table structure for table `course_ratings`
--

CREATE TABLE `course_ratings` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_ratings`
--

INSERT INTO `course_ratings` (`id`, `course_id`, `student_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 6, 5, 'Excellent intro to web dev!', '2026-04-07 22:25:09'),
(2, 3, 6, 4, 'Very detailed PHP content.', '2026-04-07 22:25:09'),
(3, 5, 6, 5, 'AI concepts were explained perfectly.', '2026-04-07 22:25:09'),
(4, 4, 7, 3, 'A bit fast-paced for me.', '2026-04-07 22:25:09'),
(5, 11, 7, 5, 'Loved the blockchain module!', '2026-04-07 22:25:09'),
(6, 2, 2, 4, 'Good course, very helpful.', '2026-04-07 22:25:09'),
(7, 6, 10, 5, 'Amazing graphic design tips!', '2026-04-07 22:25:09'),
(8, 12, 7, 5, 'Best advanced JS course!', '2026-04-14 22:18:19'),
(9, 12, 8, 5, 'Patterns are super clear.', '2026-04-14 22:18:19'),
(10, 16, 7, 5, 'GraphQL made easy.', '2026-04-14 22:18:19'),
(11, 16, 9, 5, 'Highly recommend for backend devs.', '2026-04-14 22:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `engagement_scores`
--

CREATE TABLE `engagement_scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `login_frequency` decimal(10,2) DEFAULT 0.00,
  `avg_time_spent` decimal(10,2) DEFAULT 0.00,
  `avg_quiz_score` decimal(10,2) DEFAULT 0.00,
  `course_completion_rate` decimal(10,2) DEFAULT 0.00,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `engagement_scores`
--

INSERT INTO `engagement_scores` (`id`, `student_id`, `score`, `login_frequency`, `avg_time_spent`, `avg_quiz_score`, `course_completion_rate`, `calculated_at`) VALUES
(1, 2, 24.87, 0.23, 500.00, 0.00, 0.33, '2026-04-14 22:18:36'),
(2, 6, 32.05, 0.69, 233.33, 0.00, 0.33, '2026-04-14 22:18:36'),
(3, 7, 43.33, 0.23, 1300.00, 0.00, 0.33, '2026-04-14 22:18:36'),
(4, 8, 6.67, 0.00, 0.00, 0.00, 0.33, '2026-04-14 22:18:36'),
(5, 9, 6.67, 0.00, 0.00, 0.00, 0.33, '2026-04-14 22:18:36'),
(6, 10, 6.67, 0.00, 0.00, 0.00, 0.33, '2026-04-14 22:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `course_id`, `student_id`, `enrolled_at`, `completed`) VALUES
(32, 2, 6, '2026-04-14 22:08:39', 1),
(33, 13, 6, '2026-04-14 22:08:39', 0),
(34, 14, 6, '2026-04-14 22:08:39', 0),
(35, 4, 7, '2026-04-14 22:08:39', 1),
(36, 17, 7, '2026-04-14 22:08:39', 0),
(37, 19, 7, '2026-04-14 22:08:39', 0),
(38, 6, 2, '2026-04-14 22:08:39', 1),
(39, 22, 2, '2026-04-14 22:08:39', 0),
(40, 26, 2, '2026-04-14 22:08:39', 0),
(41, 7, 8, '2026-04-14 22:08:39', 1),
(42, 11, 8, '2026-04-14 22:08:39', 0),
(43, 15, 8, '2026-04-14 22:08:39', 0),
(44, 10, 9, '2026-04-14 22:08:39', 1),
(45, 23, 9, '2026-04-14 22:08:39', 0),
(46, 24, 9, '2026-04-14 22:08:39', 0),
(47, 8, 10, '2026-04-14 22:08:39', 1),
(48, 15, 10, '2026-04-14 22:08:39', 0),
(49, 16, 10, '2026-04-14 22:08:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`id`, `user_id`, `login_at`) VALUES
(1, 3, '2026-04-07 22:28:17'),
(2, 6, '2026-04-14 21:27:33'),
(3, 3, '2026-04-14 21:54:33'),
(4, 6, '2026-04-14 21:57:13'),
(5, 7, '2026-04-14 22:10:10'),
(6, 2, '2026-04-14 22:10:50'),
(7, 6, '2026-04-14 22:16:09'),
(8, 3, '2026-04-14 22:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `created_at`) VALUES
(1, 2, 'What does HTML stand for?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Hyperlink Text Management Language', 'Home Tool Markup Language', 'A', '2026-04-07 22:25:09'),
(2, 2, 'Which tag is used for the largest heading in HTML?', '<h6>', '<head>', '<heading>', '<h1>', 'D', '2026-04-07 22:25:09'),
(3, 2, 'How do you create a function in JavaScript?', 'function:myFunction()', 'function = myFunction()', 'function myFunction()', 'new function myFunction()', 'C', '2026-04-07 22:25:09'),
(4, 3, 'What does PHP stand for?', 'Personal Home Page', 'Hypertext Preprocessor', 'Pretext Hypertext Processor', 'Private Hosting Page', 'B', '2026-04-07 22:25:09'),
(5, 3, 'Which superglobal is used to collect form data?', '$_GET', '$_POST', '$_REQUEST', 'All of the above', 'D', '2026-04-07 22:25:09'),
(6, 3, 'How do you start a session in PHP?', 'session_start()', 'start_session()', 'begin_session()', 'init_session()', 'A', '2026-04-07 22:25:09'),
(7, 4, 'Which keyword is used to create a function in Python?', 'func', 'define', 'def', 'function', 'C', '2026-04-07 22:25:09'),
(8, 4, 'What is the correct file extension for Python files?', '.pt', '.py', '.pyt', '.pyth', 'B', '2026-04-07 22:25:09'),
(9, 4, 'Which of these is used to output text in Python?', 'print()', 'echo()', 'console.log()', 'printf()', 'A', '2026-04-07 22:25:09'),
(10, 5, 'What is AI?', 'Artificial Intelligence', 'Automatic Information', 'Advanced Integration', 'Automated Interface', 'A', '2026-04-07 22:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `title`, `created_at`) VALUES
(2, 2, 'Assessment for Web Development 101', '2026-04-07 22:25:09'),
(3, 3, 'Assessment for Advanced PHP & MySQL', '2026-04-07 22:25:09'),
(4, 4, 'Assessment for Python for Data Science', '2026-04-07 22:25:09'),
(5, 5, 'Assessment for Introduction to AI', '2026-04-07 22:25:09'),
(6, 6, 'Assessment for Graphic Design Basics', '2026-04-07 22:25:09'),
(7, 7, 'Assessment for Cybersecurity Essentials', '2026-04-07 22:25:09'),
(8, 8, 'Assessment for Cloud Computing with AWS', '2026-04-07 22:25:09'),
(9, 9, 'Assessment for Mobile App Development with Flutter', '2026-04-07 22:25:09'),
(10, 10, 'Assessment for Digital Marketing 101', '2026-04-07 22:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `score` decimal(10,5) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`id`, `user_id`, `resource_id`, `score`, `rank`, `generated_at`) VALUES
(163, 2, 12, 0.75857, 1, '2026-04-14 22:18:44'),
(164, 2, 38, 0.43655, 2, '2026-04-14 22:18:44'),
(165, 2, 18, 0.10297, 3, '2026-04-14 22:18:44'),
(166, 2, 17, 0.10297, 4, '2026-04-14 22:18:44'),
(167, 6, 4, 0.70711, 1, '2026-04-14 22:18:44'),
(168, 6, 28, 0.23267, 2, '2026-04-14 22:18:44'),
(169, 6, 5, 0.20944, 3, '2026-04-14 22:18:44'),
(170, 6, 6, 0.20944, 4, '2026-04-14 22:18:44'),
(171, 6, 26, 0.19634, 5, '2026-04-14 22:18:44'),
(172, 6, 32, 0.10821, 6, '2026-04-14 22:18:44'),
(173, 7, 8, 0.74868, 1, '2026-04-14 22:18:44'),
(174, 7, 33, 0.41227, 2, '2026-04-14 22:18:44'),
(175, 7, 31, 0.15732, 3, '2026-04-14 22:18:44'),
(176, 7, 30, 0.15732, 4, '2026-04-14 22:18:44'),
(177, 7, 32, 0.14259, 5, '2026-04-14 22:18:44'),
(178, 8, 14, 0.70711, 1, '2026-04-14 22:18:44'),
(179, 8, 22, 0.53073, 2, '2026-04-14 22:18:44'),
(180, 8, 21, 0.53073, 3, '2026-04-14 22:18:44'),
(181, 9, 20, 0.86813, 1, '2026-04-14 22:18:44'),
(182, 9, 36, 0.33533, 2, '2026-04-14 22:18:44'),
(183, 9, 37, 0.26092, 3, '2026-04-14 22:18:44'),
(184, 10, 16, 0.75649, 1, '2026-04-14 22:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `resource_type` enum('file','link') NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `course_id`, `title`, `resource_type`, `path`, `link`, `tags`, `created_at`) VALUES
(3, 2, 'Mozilla Developer Network (MDN)', 'link', NULL, 'https://developer.mozilla.org/en-US/docs/Learn', 'html,css,javascript,frontend,web-dev', '2026-04-07 22:25:09'),
(4, 2, 'YouTube: HTML & CSS Full Course', 'link', NULL, 'https://www.youtube.com/watch?v=mU6anWqZJcc', 'html,css,javascript,frontend,web-dev', '2026-04-07 22:25:09'),
(5, 3, 'PHP.net Documentation', 'link', NULL, 'https://www.php.net/docs.php', 'php,mysql,backend,server,sql,php-dev', '2026-04-07 22:25:09'),
(6, 3, 'YouTube: PHP CRUD Tutorial', 'link', NULL, 'https://www.youtube.com/watch?v=3xRMUDC74Cw', 'php,mysql,backend,server,sql,php-dev', '2026-04-07 22:25:09'),
(7, 4, 'Python Data Science Handbook', 'link', NULL, 'https://jakevdp.github.io/PythonDataScienceHandbook/', 'python,data-science,numpy,pandas,analysis', '2026-04-07 22:25:09'),
(8, 4, 'YouTube: Python for Beginners', 'link', NULL, 'https://www.youtube.com/watch?v=_uQrJ0TkZlc', 'python,data-science,numpy,pandas,analysis', '2026-04-07 22:25:09'),
(9, 5, 'AI for Everyone (Coursera)', 'link', NULL, 'https://www.coursera.org/learn/ai-for-everyone', 'ai,machine-learning,theory,intelligence', '2026-04-07 22:25:09'),
(10, 5, 'YouTube: What is AI?', 'link', NULL, 'https://www.youtube.com/watch?v=ad79nYk2keg', 'ai,machine-learning,theory,intelligence', '2026-04-07 22:25:09'),
(11, 6, 'Canva Design School', 'link', NULL, 'https://www.canva.com/designschool/', 'design,graphic,creative,adobe,branding', '2026-04-07 22:25:09'),
(12, 6, 'YouTube: Graphic Design Principles', 'link', NULL, 'https://www.youtube.com/watch?v=YqQx75OPRa0', 'design,graphic,creative,adobe,branding', '2026-04-07 22:25:09'),
(13, 7, 'Cisco Networking Academy', 'link', NULL, 'https://www.netacad.com/courses/cybersecurity', 'cybersecurity,network,security,protection,firewall', '2026-04-07 22:25:09'),
(14, 7, 'YouTube: Cybersecurity for Beginners', 'link', NULL, 'https://www.youtube.com/watch?v=inWWhr5phPQ', 'cybersecurity,network,security,protection,firewall', '2026-04-07 22:25:09'),
(15, 8, 'AWS Documentation', 'link', NULL, 'https://docs.aws.amazon.com/', 'cloud,aws,infrastructure,devops,s3,ec2', '2026-04-07 22:25:09'),
(16, 8, 'YouTube: AWS Certified Cloud Practitioner', 'link', NULL, 'https://www.youtube.com/watch?v=3hLmDS179YE', 'cloud,aws,infrastructure,devops,s3,ec2', '2026-04-07 22:25:09'),
(17, 9, 'Flutter Documentation', 'link', NULL, 'https://docs.flutter.dev/', 'flutter,mobile,dart,app,frontend,ui', '2026-04-07 22:25:09'),
(18, 9, 'YouTube: Flutter Tutorial for Beginners', 'link', NULL, 'https://www.youtube.com/watch?v=VPvVD8t02U8', 'flutter,mobile,dart,app,frontend,ui', '2026-04-07 22:25:09'),
(19, 10, 'HubSpot Academy', 'link', NULL, 'https://academy.hubspot.com/', 'marketing,seo,digital,strategy,growth', '2026-04-07 22:25:09'),
(20, 10, 'YouTube: Digital Marketing Course', 'link', NULL, 'https://www.youtube.com/watch?v=nU-IIXBWln4', 'marketing,seo,digital,strategy,growth', '2026-04-07 22:25:09'),
(21, 11, 'Blockchain Council', 'link', NULL, 'https://www.blockchain-council.org/', 'blockchain,crypto,ledger,web3,decentralized', '2026-04-07 22:25:09'),
(22, 11, 'YouTube: How Blockchain Works', 'link', NULL, 'https://www.youtube.com/watch?v=SSo_EIwHSd4', 'blockchain,crypto,ledger,web3,decentralized', '2026-04-07 22:25:09'),
(23, 11, 'Web3 and Blockchain Basics', 'link', NULL, 'https://example.com/web3', 'blockchain,crypto,web3,decentralized,ethereum', '2026-04-14 22:08:20'),
(24, 12, 'Advanced JavaScript Guide', 'link', NULL, 'https://example.com/js-adv', 'javascript,js,patterns,advanced,web', '2026-04-14 22:08:20'),
(25, 13, 'Node.js Microservices Course', 'link', NULL, 'https://example.com/node-micro', 'node,backend,server,microservices,api', '2026-04-14 22:08:20'),
(26, 14, 'React Redux Mastery', 'link', NULL, 'https://example.com/react-redux', 'react,frontend,state,redux,javascript', '2026-04-14 22:08:20'),
(27, 15, 'Docker Containerization', 'link', NULL, 'https://example.com/docker', 'docker,container,devops,deployment,linux', '2026-04-14 22:08:20'),
(28, 16, 'GraphQL Schema Design', 'link', NULL, 'https://example.com/graphql', 'graphql,api,backend,query,json', '2026-04-14 22:08:20'),
(29, 17, 'Scikit-Learn ML Intro', 'link', NULL, 'https://example.com/skl', 'ml,python,data,scikit-learn,modeling', '2026-04-14 22:08:20'),
(30, 18, 'TensorFlow Neural Networks', 'link', NULL, 'https://example.com/tf', 'deep-learning,tensorflow,neural-networks,ai,python', '2026-04-14 22:08:20'),
(31, 19, 'Natural Language Processing Tips', 'link', NULL, 'https://example.com/nlp', 'nlp,text-analysis,ai,python,language', '2026-04-14 22:08:20'),
(32, 20, 'D3 Data Viz', 'link', NULL, 'https://example.com/d3', 'd3,visualization,data,javascript', '2026-04-14 22:08:20'),
(33, 21, 'Statistics for DS', 'link', NULL, 'https://example.com/stats', 'statistics,math,data-science,analysis,python', '2026-04-14 22:08:20'),
(34, 22, 'UI/UX Prototype Design', 'link', NULL, 'https://example.com/uiux', 'ui,ux,design,prototype,user-experience', '2026-04-14 22:08:20'),
(35, 23, 'Social Media Growth', 'link', NULL, 'https://example.com/sm', 'social-media,marketing,strategy,growth,engagement', '2026-04-14 22:08:20'),
(36, 24, 'Content Marketing Masterclass', 'link', NULL, 'https://example.com/content', 'content,marketing,writing,strategy,conversion', '2026-04-14 22:08:20'),
(37, 25, 'Advanced SEO Ranking', 'link', NULL, 'https://example.com/seo-adv', 'seo,ranking,search,marketing,google', '2026-04-14 22:08:20'),
(38, 26, 'Logo & Branding Identity', 'link', NULL, 'https://example.com/branding', 'branding,design,logo,identity,creative', '2026-04-14 22:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `resource_completions`
--

CREATE TABLE `resource_completions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_completions`
--

INSERT INTO `resource_completions` (`id`, `student_id`, `resource_id`, `completed_at`) VALUES
(8, 6, 3, '2026-04-14 22:08:39'),
(9, 6, 4, '2026-04-14 22:08:39'),
(11, 7, 7, '2026-04-14 22:08:39'),
(12, 7, 8, '2026-04-14 22:08:39'),
(14, 2, 11, '2026-04-14 22:08:39'),
(15, 2, 12, '2026-04-14 22:08:39'),
(17, 8, 13, '2026-04-14 22:08:39'),
(18, 8, 14, '2026-04-14 22:08:39'),
(20, 9, 19, '2026-04-14 22:08:39'),
(21, 9, 20, '2026-04-14 22:08:39'),
(23, 10, 15, '2026-04-14 22:08:39'),
(24, 10, 16, '2026-04-14 22:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `resource_views`
--

CREATE TABLE `resource_views` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `time_spent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_views`
--

INSERT INTO `resource_views` (`id`, `resource_id`, `student_id`, `start_time`, `end_time`, `time_spent`) VALUES
(28, 3, 6, '2026-04-14 22:08:39', NULL, 300),
(29, 25, 6, '2026-04-14 22:08:39', NULL, 400),
(30, 7, 7, '2026-04-14 22:08:39', NULL, 600),
(31, 29, 7, '2026-04-14 22:08:39', NULL, 700),
(32, 11, 2, '2026-04-14 22:08:39', NULL, 200),
(33, 34, 2, '2026-04-14 22:08:39', NULL, 300),
(34, 13, 8, '2026-04-14 22:08:39', NULL, 500),
(35, 23, 8, '2026-04-14 22:08:39', NULL, 600),
(36, 19, 9, '2026-04-14 22:08:39', NULL, 300),
(37, 35, 9, '2026-04-14 22:08:39', NULL, 400),
(38, 15, 10, '2026-04-14 22:08:39', NULL, 600),
(39, 27, 10, '2026-04-14 22:08:39', NULL, 700);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('learner','instructor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Shakeeb', 'U', 'shakeeb@example.com', 'shakerbu', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'instructor', '2026-04-07 22:25:09'),
(2, 'Sonu', 'K', 'sonu@example.com', 'sonu', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09'),
(3, 'Dr.', 'Smith', 'alice@example.com', 'alice', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'instructor', '2026-04-07 22:25:09'),
(4, 'Prof.', 'Johnson', 'bob@example.com', 'bob', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'instructor', '2026-04-07 22:25:09'),
(5, 'Ms.', 'Lee', 'cat@example.com', 'catherine', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'instructor', '2026-04-07 22:25:09'),
(6, 'John', 'Doe', 'john@example.com', 'john', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09'),
(7, 'Jane', 'Smith', 'jane@example.com', 'jane', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09'),
(8, 'Mark', 'Wilson', 'mark@example.com', 'mark', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09'),
(9, 'Emily', 'Davis', 'emily@example.com', 'emily', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09'),
(10, 'Chris', 'Brown', 'chris@example.com', 'chris', '$2y$10$OQBpgz.hpAIoQmGv.ZB7p.2X0S5A809FtCOC7cRYPcGV5V0zVVMaC', 'learner', '2026-04-07 22:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_clusters`
--

CREATE TABLE `user_clusters` (
  `user_id` int(11) NOT NULL,
  `cluster_id` int(11) NOT NULL,
  `silhouette_score` decimal(10,5) DEFAULT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_clusters`
--

INSERT INTO `user_clusters` (`user_id`, `cluster_id`, `silhouette_score`, `calculated_at`) VALUES
(2, 0, 0.55694, '2026-04-14 22:18:41'),
(6, 0, 0.55694, '2026-04-14 22:18:41'),
(7, 1, 0.55694, '2026-04-14 22:18:41'),
(8, 2, 0.55694, '2026-04-14 22:18:41'),
(9, 2, 0.55694, '2026-04-14 22:18:41'),
(10, 2, 0.55694, '2026-04-14 22:18:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cluster_log`
--
ALTER TABLE `cluster_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_ibfk_1` (`instructor_id`);

--
-- Indexes for table `course_ratings`
--
ALTER TABLE `course_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_course` (`course_id`,`student_id`),
  ADD KEY `course_ratings_ibfk_2` (`student_id`);

--
-- Indexes for table `engagement_scores`
--
ALTER TABLE `engagement_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `engagement_scores_ibfk_1` (`student_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_ibfk_1` (`course_id`),
  ADD KEY `enrollments_ibfk_2` (`student_id`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_log_ibfk_1` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_ibfk_1` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_ibfk_1` (`course_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_attempts_ibfk_1` (`quiz_id`),
  ADD KEY `quiz_attempts_ibfk_2` (`student_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_resource` (`user_id`,`resource_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resources_ibfk_1` (`course_id`);

--
-- Indexes for table `resource_completions`
--
ALTER TABLE `resource_completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_resource` (`student_id`,`resource_id`),
  ADD KEY `resource_completions_ibfk_2` (`resource_id`);

--
-- Indexes for table `resource_views`
--
ALTER TABLE `resource_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resource_views_ibfk_1` (`resource_id`),
  ADD KEY `resource_views_ibfk_2` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_clusters`
--
ALTER TABLE `user_clusters`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cluster_log`
--
ALTER TABLE `cluster_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `course_ratings`
--
ALTER TABLE `course_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `engagement_scores`
--
ALTER TABLE `engagement_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `resource_completions`
--
ALTER TABLE `resource_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `resource_views`
--
ALTER TABLE `resource_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `course_ratings`
--
ALTER TABLE `course_ratings`
  ADD CONSTRAINT `course_ratings_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_ratings_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `engagement_scores`
--
ALTER TABLE `engagement_scores`
  ADD CONSTRAINT `engagement_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `login_log`
--
ALTER TABLE `login_log`
  ADD CONSTRAINT `login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `resource_completions`
--
ALTER TABLE `resource_completions`
  ADD CONSTRAINT `resource_completions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resource_completions_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resource_views`
--
ALTER TABLE `resource_views`
  ADD CONSTRAINT `resource_views_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resource_views_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_clusters`
--
ALTER TABLE `user_clusters`
  ADD CONSTRAINT `user_clusters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
