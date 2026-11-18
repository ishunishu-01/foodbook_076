-- Database for FoodBook Mini Project
-- Student: Ishara Nishshanka (ITT/2021/076)
-- ICT 1209 - Web Technologies

CREATE DATABASE IF NOT EXISTS `foodbook`;
USE `foodbook`;

-- Drop existing tables if re-importing
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `users`;

-- Users table
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recipes table
CREATE TABLE `recipes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `category` VARCHAR(50) NOT NULL,
  `ingredients` TEXT NOT NULL,
  `instructions` TEXT NOT NULL,
  `cooking_time` INT DEFAULT 30,
  `servings` INT DEFAULT 4,
  `image_url` VARCHAR(500),
  `rating` DECIMAL(3,1) DEFAULT 4.5,
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact messages table
CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) DEFAULT 'General Inquiry',
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default login credentials:
-- Email: itt2021076@tec.rjt.ac.lk
-- Password: ITT/2021/076
INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'ishara_nishshanka', 'itt2021076@tec.rjt.ac.lk', '$2y$10$Eub6ssXcTTOp.CaX35ujxeOwnGTjSb8qZTiV6ccZ.6XgMWkV8HJhS', NOW()),
(2, 'kamal_perera', 'kamal@gmail.com', '$2y$10$Eub6ssXcTTOp.CaX35ujxeOwnGTjSb8qZTiV6ccZ.6XgMWkV8HJhS', NOW());

-- Initial sample recipes
INSERT INTO `recipes` (`id`, `user_id`, `title`, `description`, `category`, `ingredients`, `instructions`, `cooking_time`, `servings`, `image_url`, `rating`, `views`) VALUES
(1, 1, 'Creamy Pasta Carbonara', 'A simple and popular Italian pasta dish made with eggs, cheese, and bacon.', 'Dinner', '• 350g spaghetti\n• 150g bacon or pancetta\n• 3 eggs\n• 50g grated cheese (Parmesan)\n• 2 cloves garlic\n• Salt and black pepper\n• 1 tbsp cooking oil', '1. Boil spaghetti in salted water for 8-10 minutes until soft.\n2. In a pan, fry chopped garlic and bacon in a little oil until crispy.\n3. In a bowl, beat eggs with grated cheese, salt, and black pepper.\n4. Drain the pasta and immediately add to the pan with bacon.\n5. Turn off the heat and quickly stir in the egg mixture so it forms a smooth sauce.\n6. Serve warm with extra black pepper on top.', 25, 3, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600', 4.8, 48),

(2, 1, 'Chocolate Lava Cake', 'Delicious homemade chocolate cake with a warm melted chocolate center.', 'Desserts', '• 120g dark chocolate\n• 80g butter\n• 2 eggs\n• 50g sugar\n• 30g flour\n• Butter and cocoa powder for ramekins', '1. Preheat your oven to 200°C. Grease small baking cups with butter and dust with cocoa powder.\n2. Melt dark chocolate and butter together in a heatproof bowl.\n3. Whisk eggs and sugar together until slightly thick.\n4. Mix melted chocolate into egg mixture, then gently fold in flour.\n5. Pour batter into cups and bake for 10-12 minutes until edges are cooked but center remains soft.\n6. Let cool for 2 minutes and turn out onto plates to serve.', 20, 2, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600', 4.9, 75),

(3, 1, 'Avocado Toast with Poached Egg', 'Quick and healthy breakfast toast topped with fresh mashed avocado and egg.', 'Breakfast', '• 2 slices brown or white bread\n• 1 ripe avocado\n• 2 eggs\n• 1 tsp lime juice\n• Salt and pepper\n• Pinch of chili flakes', '1. Toast the bread slices until crispy.\n2. Cut avocado, scoop out the flesh into a bowl, and mash with fork. Add lime juice, salt, and pepper.\n3. In a small pot of simmering water, poach eggs for about 3 minutes until the egg whites set.\n4. Spread mashed avocado evenly over toast.\n5. Carefully place poached eggs on top and sprinkle with chili flakes.', 15, 2, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600', 4.7, 34),

(4, 2, 'Spicy Chicken Curry', 'Flavorful Sri Lankan style chicken curry cooked with coconut milk and spices.', 'Dinner', '• 500g chicken cut into pieces\n• 1 onion, chopped\n• 2 green chilies\n• 1 tbsp ginger-garlic paste\n• 2 tbsp curry powder\n• 1/2 tsp turmeric powder\n• 1 cup coconut milk\n• Curry leaves and rampe\n• 2 tbsp cooking oil\n• Salt to taste', '1. Clean chicken and marinate with curry powder, turmeric, and salt for 15 minutes.\n2. Heat oil in a cooking pot, add onion, green chilies, ginger-garlic paste, and curry leaves.\n3. Add chicken pieces and fry for 5 minutes until sealed.\n4. Add 1/2 cup water, cover with lid, and cook on medium heat for 15 minutes.\n5. Pour in coconut milk and simmer for another 5-7 minutes until gravy thickens.\n6. Serve hot with rice or bread.', 40, 4, 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600', 4.8, 112),

(5, 2, 'Fresh Greek Salad', 'Crisp fresh cucumber, tomatoes, olives, and cheese tossed in olive oil.', 'Lunch', '• 2 large tomatoes, sliced\n• 1 cucumber, diced\n• 1 red onion, sliced\n• 100g black olives\n• 100g feta cheese\n• 2 tbsp olive oil\n• 1 tbsp lemon juice or vinegar\n• 1/2 tsp dried oregano\n• Salt and black pepper', '1. Wash all vegetables thoroughly.\n2. In a salad bowl, combine chopped tomatoes, cucumber, onion, and olives.\n3. In a small cup, mix olive oil, lemon juice, salt, and pepper.\n4. Pour dressing over vegetables and toss gently.\n5. Place cubes or slice of feta cheese on top and sprinkle oregano.\n6. Serve fresh as a side or light lunch.', 15, 3, 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600', 4.5, 29),

(6, 1, 'Tropical Mango Smoothie', 'Sweet and chilled fresh mango smoothie blended with yogurt and milk.', 'Drinks', '• 1 large ripe mango, peeled and diced\n• 1 small banana\n• 1/2 cup plain yogurt\n• 1/2 cup chilled milk\n• 1 tbsp honey (optional)\n• Ice cubes', '1. Peel and chop ripe mango and banana.\n2. Place fruits, yogurt, milk, and honey into a blender.\n3. Add a few ice cubes.\n4. Blend on high speed for about 1 minute until creamy and smooth.\n5. Pour into glasses and serve cold immediately.', 10, 2, 'https://images.unsplash.com/photo-1553530979-3b6eca8abad9?w=600', 4.6, 52);

-- Sample contact messages
INSERT INTO `messages` (`name`, `email`, `subject`, `message`) VALUES
('Nisal Bandara', 'nisal@gmail.com', 'Question about recipes', 'Hi Ishara, your recipe book project looks great! Can we also filter recipes by cooking time?'),
('Anjali Silva', 'anjali.s@yahoo.com', 'Great website', 'I tried the lava cake recipe today, it came out really well. Thank you!');
