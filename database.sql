-- ============================================================
-- FoodBook - Digital Recipe Book Database Schema
-- Student Name : Ishara Nishshanka
-- Registration : ITT/2021/076
-- Course       : ICT 1209 – Web Technologies Mini Project
-- Faculty      : Faculty of Technology, Rajarata University of Sri Lanka
-- Department   : Department of ICT
-- Database Name: foodbook
-- ============================================================

CREATE DATABASE IF NOT EXISTS `foodbook` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `foodbook`;

-- ------------------------------------------------------------
-- Table structure for table `users`
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table structure for table `recipes`
-- ------------------------------------------------------------
CREATE TABLE `recipes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `category` VARCHAR(50) NOT NULL,
  `ingredients` TEXT NOT NULL,
  `instructions` TEXT NOT NULL,
  `cooking_time` INT(11) NOT NULL DEFAULT 30,
  `servings` INT(11) NOT NULL DEFAULT 4,
  `image_url` VARCHAR(500) DEFAULT NULL,
  `rating` DECIMAL(3,1) NOT NULL DEFAULT 4.5,
  `views` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_recipes_users` (`user_id`),
  CONSTRAINT `fk_recipes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table structure for table `messages`
-- ------------------------------------------------------------
CREATE TABLE `messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(200) DEFAULT 'General Inquiry',
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Dumping data for table `users`
-- Default password: Password123
-- ------------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'ishara_admin', 'ishara@foodbook.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFj05eK8s7rS6WpL0cbn8hJd5y7yC6w2', '2026-11-18 10:00:00'),
(2, 'chef_kamal', 'kamal@foodbook.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFj05eK8s7rS6WpL0cbn8hJd5y7yC6w2', '2026-11-18 10:30:00');

-- ------------------------------------------------------------
-- Dumping data for table `recipes`
-- ------------------------------------------------------------
INSERT INTO `recipes` (`id`, `user_id`, `title`, `description`, `category`, `ingredients`, `instructions`, `cooking_time`, `servings`, `image_url`, `rating`, `views`, `created_at`) VALUES
(1, 1, 'Creamy Pasta Carbonara', 'A classic Italian pasta delicacy with a rich silky egg emulsion, crisp pancetta, and freshly cracked black pepper.', 'Dinner', '• 400g spaghetti or linguine\n• 200g guanciale or smoked pancetta, diced\n• 4 fresh farm egg yolks + 1 whole egg\n• 100g freshly grated Pecorino Romano cheese\n• 2 cloves garlic, peeled\n• Freshly crushed black peppercorns\n• Sea salt for pasta water', '1. Bring a large pot of salted water to a rolling boil and cook pasta until al dente (about 8-9 minutes).\n2. Heat a large skillet over medium-low heat and cook pancetta until crisp and golden brown.\n3. In a medium mixing bowl, whisk whole egg, yolks, grated Pecorino, and abundant black pepper.\n4. Reserve 1/2 cup of hot starchy pasta cooking water before draining pasta.\n5. Transfer hot drained pasta into skillet with pancetta off the direct heat.\n6. Pour egg mixture over pasta while tossing vigorously to create a glossy emulsion without scrambling eggs.\n7. Add splashes of reserved pasta water as needed to reach ideal creaminess.\n8. Plate immediately with extra cracked pepper and grated cheese.', 25, 4, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600', 4.9, 142, '2026-11-18 11:00:00'),

(2, 1, 'Molten Chocolate Lava Cake', 'Decadent individual chocolate cakes with rich flowing warm chocolate centers, baked to utter perfection.', 'Desserts', '• 150g premium dark chocolate (70% cocoa)\n• 100g unsalted butter, cubed\n• 2 whole eggs + 2 egg yolks\n• 60g superfine caster sugar\n• 35g all-purpose flour, sifted\n• Unsweetened cocoa powder for dusting ramekins\n• High quality vanilla ice cream for serving', '1. Generously butter 4 ceramic ramekins and coat thoroughly with sifted cocoa powder; preheat oven to 200°C (400°F).\n2. Gently melt chocolate and butter together in a heatproof bowl set over simmering water; cool slightly.\n3. Whisk eggs, egg yolks, and sugar together with an electric mixer until pale, light, and doubled in volume.\n4. Gently fold cooled chocolate mixture into whipped eggs.\n5. Sift in flour and fold delicately with a rubber spatula until just combined.\n6. Divide batter evenly among prepared ramekins and chill in refrigerator for 20 minutes.\n7. Bake for 11 to 13 minutes until edges are firmly set but center retains a gentle jiggle.\n8. Run a thin knife around edges, invert onto dessert plates, and serve immediately alongside vanilla bean ice cream.', 20, 4, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600', 4.9, 215, '2026-11-18 11:15:00'),

(3, 1, 'Sourdough Avocado Toast & Poached Egg', 'Artisanal breakfast staple featuring crushed citrus avocado, soft poached eggs, and chili flakes on rustic toasted sourdough.', 'Breakfast', '• 2 thick slices crusty artisan sourdough bread\n• 2 ripe Hass avocados\n• 2 large organic eggs\n• 1 tbsp fresh lime or lemon juice\n• 1 tbsp distilled white vinegar (for poaching)\n• Pinch of red chili flakes\n• Sea salt flakes & freshly ground pepper\n• Extra virgin olive oil for drizzling\n• Fresh microgreens or chopped chives', '1. Toast sourdough slices until golden brown with a crispy crust.\n2. Scoop avocado flesh into a bowl, add lemon juice, salt, and pepper, then mash lightly leaving subtle rustic chunks.\n3. Bring a saucepan of water to a gentle simmer (barely bubbling) and stir in white vinegar.\n4. Crack an egg into a small cup, create a gentle vortex in water with a spoon, and slide egg gently into center.\n5. Poach gently for 3 minutes until whites are firm and yolk is soft and runny.\n6. Generously spread seasoned avocado over warm toasted sourdough.\n7. Lift poached eggs with a slotted spoon, blot on paper towel, and place atop avocado toast.\n8. Sprinkle with chili flakes, coarse sea salt, microgreens, and a light ribbon of olive oil.', 15, 2, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600', 4.8, 98, '2026-11-18 11:30:00'),

(4, 2, 'Authentic Chicken Tikka Masala', 'Succulent marinated spiced chicken pieces simmered gently in a vibrant, creamy aromatic tomato and fenugreek gravy.', 'Dinner', '• 800g boneless chicken thigh, cut into bite-sized pieces\n• 1 cup plain Greek yogurt\n• 2 tbsp freshly grated ginger & garlic\n• 2 tsp garam masala\n• 1 tsp ground turmeric\n• 1 tbsp smoked paprika\n• 400g canned crushed San Marzano tomatoes\n• 200ml heavy cooking cream\n• 2 tbsp butter + 1 tbsp vegetable oil\n• 1 tbsp kasuri methi (dried fenugreek leaves)\n• Fresh cilantro leaves', '1. Combine yogurt, minced ginger, garlic, garam masala, turmeric, paprika, and salt; coat chicken and marinate for at least 1 hour.\n2. Thread chicken on skewers or broil on high wire rack at 220°C for 12-14 minutes until charred at edges.\n3. Heat butter and oil in a deep pan; sauté finely diced onions until caramelized and fragrant.\n4. Stir in crushed ginger-garlic and dry spices for 1 minute.\n5. Add crushed tomatoes, cover, and simmer sauce over medium heat for 15 minutes.\n6. Stir in heavy cream and crushed fenugreek leaves.\n7. Add charred chicken pieces and simmer gently for 8-10 minutes until chicken is cooked through and sauce clings to meat.\n8. Garnish with fresh chopped coriander and serve hot with buttery garlic naan or basmati rice.', 45, 6, 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600', 4.7, 310, '2026-11-18 11:45:00'),

(5, 2, 'Crisp Mediterranean Greek Salad', 'Crisp garden vegetables, Kalamata olives, and creamy sheep feta cheese tossed in red wine oregano vinaigrette.', 'Lunch', '• 4 ripe vine tomatoes, wedged\n• 1 crisp English cucumber, sliced into thick half-moons\n• 1 small red onion, thinly sliced into rings\n• 1 green bell pepper, deseeded and chopped\n• 150g authentic Kalamata olives\n• 200g Greek feta cheese block\n• 3 tbsp premium extra virgin olive oil\n• 1 tbsp red wine vinegar\n• 1 tsp dried wild oregano\n• Sea salt & freshly cracked black pepper', '1. Wash, dry, and chop vine tomatoes, cucumber, green pepper, and red onion.\n2. Place chopped vegetables into a large chilled salad bowl.\n3. Add Kalamata olives throughout the vegetables.\n4. In a small jar, combine extra virgin olive oil, red wine vinegar, salt, and pepper; shake vigorously to emulsify.\n5. Pour vinaigrette over vegetables and toss gently to coat evenly.\n6. Place the slab of authentic Greek feta cheese directly on top of the salad.\n7. Sprinkle generously with dried wild Greek oregano and an extra drizzle of olive oil.\n8. Serve cold alongside warm pita bread.', 15, 4, 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600', 4.6, 76, '2026-11-18 12:00:00'),

(6, 1, 'Tropical Mango Passionfruit Smoothie', 'Refreshing sun-ripened tropical mango and tangy passionfruit blended with chilled coconut water and Greek yogurt.', 'Drinks', '• 2 ripe sweet mangoes, peeled and diced\n• Pulp of 2 fresh passionfruits\n• 1 medium banana, sliced and frozen\n• 150g thick vanilla Greek yogurt\n• 200ml chilled coconut milk or almond milk\n• 1 tbsp raw organic honey\n• 1 cup crushed ice cubes\n• Fresh mint sprigs for garnish', '1. Peel and chop fresh mangoes, removing the center seed.\n2. Place frozen banana slices, diced mango, and passionfruit pulp into a high-powered blender.\n3. Add chilled Greek yogurt, coconut milk, raw honey, and crushed ice.\n4. Blend on low speed for 15 seconds, then increase to high speed for 45 seconds until silky smooth.\n5. Taste and add extra honey if desired for sweetness.\n6. Pour smoothie into chilled tall glasses.\n7. Garnish with additional passionfruit seeds and fresh fragrant mint sprigs.\n8. Serve immediately with a colorful straw.', 10, 2, 'https://images.unsplash.com/photo-1553530979-3b6eca8abad9?w=600', 4.7, 89, '2026-11-18 12:15:00');

-- ------------------------------------------------------------
-- Dumping data for table `messages`
-- ------------------------------------------------------------
INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'Recipe Contribution', 'Hello! I really love FoodBook. How can I get verified as a featured creator on your platform?', '2026-11-18 12:30:00'),
(2, 'Sarah Jenkins', 'sarah.j@example.com', 'Suggestion for App', 'Would love to see dietary tag filters like Gluten-Free and Vegan in the next release. Great work!', '2026-11-18 12:45:00');
