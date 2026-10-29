-- Dummy Data for Book Log Application
-- Run this in MySQL Workbench to populate the books table

USE book_log;

INSERT INTO books (title, author, genre, language, pages, status, rating, started_at, finished_at, ownership, purchase_place, condition, purchase_date, price, isbn, cover_url, notes, created_at, updated_at) VALUES
('The Da Vinci Code', 'Dan Brown', 'Thriller', 'English', 489, 'finished', 4, '2024-01-15', '2024-02-10', 'owned', 'Gramedia', 'new', '2024-01-10', 150000, '9780307474278', 'https://books.google.com/books/content?id=P8V6AwAAQBAJ&pg=PP1&img=1', 'Great thriller with historical elements.', NOW(), NOW()),

('Bumi Manusia', 'Pramoedya Ananta Toer', 'Historical Fiction', 'Indonesian', 535, 'finished', 5, '2024-02-15', '2024-03-20', 'owned', 'Toko Buku Bekas', 'preloved', '2024-02-10', 75000, '9789799748769', '', 'Indonesian literary masterpiece.', NOW(), NOW()),

('Atomic Habits', 'James Clear', 'Self-Help', 'English', 320, 'reading', NULL, '2024-04-01', NULL, 'owned', 'Kinokuniya', 'new', '2024-03-25', 280000, '9780735211292', 'https://books.google.com/books/content?id=2WgZDwAAQBAJ&pg=PP1&img=1', 'Currently reading chapter by chapter.', NOW(), NOW()),

('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'History', 'English', 443, 'to_read', NULL, NULL, NULL, 'owned', 'Periplus', 'new', '2024-04-15', 320000, '9780062316097', 'https://books.google.com/books/content?id=H13YBgAAQBAJ&pg=PP1&img=1', 'Waiting to start after finishing Atomic Habits.', NOW(), NOW()),

('Laskar Pelangi', 'Andrea Hirata', 'Fiction', 'Indonesian', 529, 'finished', 5, '2023-12-01', '2023-12-25', 'owned', 'Gramedia', 'new', '2023-11-28', 120000, '9789799053610', '', 'Inspiring story about education in Belitong.', NOW(), NOW()),

('The Midnight Library', 'Matt Haig', 'Fiction', 'English', 304, 'dropped', 2, '2024-03-01', NULL, 'borrowed', NULL, NULL, NULL, 0, '9780525559474', 'https://books.google.com/books/content?id=vQ2IDwAAQBAJ&pg=PP1&img=1', 'Borrowed from friend, could not finish.', NOW(), NOW()),

('Clean Code', 'Robert C. Martin', 'Technology', 'English', 464, 'reading', NULL, '2024-04-20', NULL, 'owned', 'Amazon', 'new', '2024-04-18', 450000, '9780132350884', 'https://books.google.com/books/content?id=pH_FAAAAMAAJ&pg=PP1&img=1', 'Reference book for software development.', NOW(), NOW()),

('Dune', 'Frank Herbert', 'Science Fiction', 'English', 688, 'to_read', NULL, NULL, NULL, 'wishlist', NULL, NULL, NULL, 0, '9780441172719', 'https://books.google.com/books/content?id=E20hAAAAQBAJ&pg=PP1&img=1', 'Planning to buy and read before watching the movie.', NOW(), NOW()),

('Filosofi Teras', 'Henry Manampiring', 'Philosophy', 'Indonesian', 272, 'finished', 4, '2024-01-01', '2024-01-20', 'owned', 'Gramedia', 'new', '2023-12-30', 95000, '9786020486498', '', 'Stoicism philosophy in Indonesian context.', NOW(), NOW()),

('1984', 'George Orwell', 'Dystopian', 'English', 328, 'finished', 5, '2023-10-01', '2023-10-25', 'owned', 'Toko Buku Bekas', 'preloved', '2023-09-28', 60000, '9780451524935', 'https://books.google.com/books/content?id=xtwzAAAAMAAJ&pg=PP1&img=1', 'Classic dystopian novel, very relevant today.', NOW(), NOW()),

('Gone Girl', 'Gillian Flynn', 'Thriller', 'English', 432, 'to_read', NULL, NULL, NULL, 'skimmed', 'Library', NULL, NULL, 0, '9780307588371', 'https://books.google.com/books/content?id=zD7xAAAAQBAJ&pg=PP1&img=1', 'Skimmed at library, might buy later.', NOW(), NOW()),

('Thinking, Fast and Slow', 'Daniel Kahneman', 'Psychology', 'English', 499, 'reading', NULL, '2024-03-15', NULL, 'owned', 'Kinokuniya', 'new', '2024-03-10', 350000, '9780374533557', 'https://books.google.com/books/content?id=O3JPAgAAQBAJ&pg=PP1&img=1', 'Heavy reading, taking time to understand concepts.', NOW(), NOW()),

('Pulang', 'Leila S. Chudori', 'Historical Fiction', 'Indonesian', 416, 'finished', 4, '2024-02-01', '2024-02-28', 'owned', 'Gramedia', 'new', '2024-01-28', 135000, '9786020323616', '', 'Historical fiction about 1965 events.', NOW(), NOW()),

('The Psychology of Money', 'Morgan Housel', 'Finance', 'English', 256, 'finished', 5, '2024-01-05', '2024-01-18', 'owned', 'Shopee', 'new', '2024-01-02', 180000, '9780857197689', 'https://books.google.com/books/content?id=tKm0DwAAQBAJ&pg=PP1&img=1', 'Excellent insights on personal finance behavior.', NOW(), NOW()),

('Negeri 5 Menara', 'A. Fuadi', 'Fiction', 'Indonesian', 432, 'finished', 4, '2023-11-01', '2023-11-30', 'owned', 'Gramedia', 'new', '2023-10-28', 110000, '9789792254842', '', 'Inspirational story about Islamic boarding school.', NOW(), NOW());
