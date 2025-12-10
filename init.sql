CREATE TABLE catalogue_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

INSERT INTO catalogue_admin (username,password_hash) VALUES
('amita', '$2a$12$xORF9Hh2fcIrfyCTH6fqIexTTPzIJrsOPiRxHiMsPkUJNGodlfWZS'),
('komalpreet', '$2a$12$uqBWqtUeE9AohkeMQTJM1u.BM5EFCnLd9OmM2UT0s.7Q/eYMFD1YO'),
('zara', '$2a$12$a7MStzL9ZbKxGgiujzleo.0jR7fKSjc7kF71MNWZF5EzV8WyINZ7K'),
('shalom', '$2a$12$CaUY.lVz1WtzLKwS0W/Vj.WLaeGthgonwFcX6hlJcGTwG/a2dZqVa'),
('instructor', '$2a$12$.M4Ip/lj2VglTqwhpzCALeE6LXTwzlxTHfdyymKL82As6yIItpjUm');


CREATE TABLE IF NOT EXISTS catalogue_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) DEFAULT NULL,
    title VARCHAR(50) NOT NULL,
    country VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    region VARCHAR(100) DEFAULT NULL,
    foodType VARCHAR(100) DEFAULT NULL,
    priceRange VARCHAR(50) DEFAULT NULL,
    spiceLevel VARCHAR(50) DEFAULT NULL,
    mainIngredients TEXT DEFAULT NULL,
    cookingMethod VARCHAR(255) DEFAULT NULL,
    rating DECIMAL(5,1) DEFAULT NULL
);

INSERT INTO catalogue_items (image, title, country, description, region, foodType, priceRange, spiceLevel, mainIngredients, cookingMethod, rating) VALUES
('sushi.jpg', 'Sushi', 'Japan', 'A traditional Japanese dish consisting of vinegared rice combined with various ingredients such as raw fish, vegetables, and seaweed.', 'East Asia', 'Seafood', '$$$', 'Mild', 'Rice, Raw Fish, Seaweed', 'Raw Preparation', 4.8),
('tacos.jpg', 'Tacos', 'Mexico', 'A traditional Mexican dish made of small hand-sized corn or wheat tortillas topped with a variety of fillings such as beef, pork, chicken, vegetables, and cheese.', 'North America', 'Street Food', '$', 'Medium', 'Tortillas, Meat, Vegetables', 'Grilled/Fried', 4.5),
('pasta.jpg', 'Pasta', 'Italy', 'An Italian staple food made from wheat flour mixed with water or eggs and formed into various shapes. Commonly served with sauces like marinara or Alfredo.', 'Europe', 'Main Course', '$$', 'Mild', 'Wheat Flour, Eggs, Sauce', 'Boiled', 4.7),
('curry.jpg', 'Curry', 'India', 'A flavorful dish originating from the Indian subcontinent that combines spices, herbs, and other ingredients to create a rich and aromatic sauce served with rice or bread.', 'South Asia', 'Spicy Dish', '$$', 'Hot', 'Spices, Meat/Vegetables, Yogurt', 'Simmered', 4.6),
('paella.jpg', 'Paella', 'Spain', 'A traditional Spanish rice dish that includes a variety of ingredients such as seafood, chicken, rabbit, vegetables, and saffron for flavor and color.', 'Europe', 'Rice Dish', '$$$', 'Mild', 'Rice, Seafood/Meat, Saffron', 'Simmered/Baked', 4.4),
('poutine.jpg', 'Poutine', 'Canada', 'A Canadian dish consisting of French fries topped with cheese curds and smothered in brown gravy, creating a savory and indulgent comfort food.', 'North America', 'Comfort Food', '$', 'Mild', 'Potatoes, Cheese Curds, Gravy', 'Fried/Assembled', 4.3),
('baklava.jpg', 'Baklava', 'Turkey', 'A rich, sweet dessert pastry made of layers of filo filled with chopped nuts and sweetened with honey or syrup, popular in Middle Eastern and Mediterranean cuisines.', 'Middle East', 'Dessert', '$$', 'Mild', 'Filo Dough, Nuts, Honey/Syrup', 'Baked', 4.9),
('kimchi.jpg', 'Kimchi', 'Korea', 'A traditional Korean side dish made from fermented vegetables, primarily napa cabbage and Korean radishes, seasoned with chili powder, garlic, ginger, and other spices.', 'East Asia', 'Side Dish', '$', 'Hot', 'Cabbage, Radish, Spices', 'Fermented', 4.2),
('croissant.jpg', 'Croissant', 'France', 'A buttery, flaky, and crescent-shaped pastry that is a staple of French bakeries, often enjoyed for breakfast or as a snack.', 'Europe', 'Pastry', '$$', 'Mild', 'Flour, Butter, Yeast', 'Baked', 4.5),
('feijoada.jpg', 'Feijoada', 'Brazil', 'A hearty Brazilian stew made with black beans, pork, and beef, typically served with rice, collard greens, and orange slices.', 'South America', 'Stew', '$$', 'Mild', 'Black Beans, Pork, Beef', 'Simmered', 4.4),
('dim_sum.jpg', 'Dim Sum', 'China', 'A variety of bite-sized dishes traditionally served in small steamer baskets or on small plates, often enjoyed during brunch.', 'East Asia', 'Snack', '$$', 'Mild', 'Dumplings, Buns, Various Fillings', 'Steamed/Fried', 4.6),
('bbq_ribs.jpg', 'BBQ Ribs', 'USA', 'Tender pork ribs slow-cooked and coated with a smoky barbecue sauce, a popular dish in American cuisine.', 'North America', 'Grilled Meat', '$$$', 'Medium', 'Pork Ribs, Barbecue Sauce', 'Grilled/Smoked', 4.7),
('gelato.jpg', 'Gelato', 'Italy', 'A creamy and dense Italian-style ice cream made with milk, sugar, and various flavorings, known for its rich texture and intense flavors.', 'Europe', 'Dessert', '$$', 'Mild', 'Milk, Sugar, Flavorings', 'Churned/Frozen', 4.8),
('shakshuka.jpg', 'Shakshuka', 'Middle East', 'A flavorful dish of poached eggs in a spicy tomato and pepper sauce, often enjoyed for breakfast or brunch.', 'Middle East', 'Breakfast Dish', '$', 'Medium', 'Eggs, Tomatoes, Peppers, Spices', 'Simmered/Poached', 4.5),
('fish_and_chips.jpg', 'Fish and Chips', 'UK', 'A classic British dish consisting of battered and deep-fried fish served with thick-cut fries, often accompanied by tartar sauce and malt vinegar.', 'Europe', 'Fast Food', '$$', 'Mild', 'Fish, Potatoes, Batter', 'Fried', 4.3),
('tom_yum_soup.jpg', 'Tom Yum Soup', 'Thailand', 'A hot and sour Thai soup made with shrimp, mushrooms, tomatoes, lemongrass'),
('goulash.jpg', 'Goulash', 'Hungary', 'A traditional Hungarian stew made with beef, onions, paprika, and other spices, often served with potatoes or noodles.', 'Europe', 'Stew', '$$', 'Medium', 'Beef, Onions, Paprika', 'Simmered', 4.4),
('ceviche.jpg', 'Ceviche', 'Peru', 'A refreshing dish made from fresh raw fish cured in citrus juices, typically mixed with onions, cilantro, and chili peppers.', 'South America', 'Seafood', '$$', 'Hot', 'Raw Fish, Citrus Juice, Onions', 'Cured', 4.6),
('bratwurst.jpg', 'Bratwurst', 'Germany', 'A type of German sausage made from pork, beef, or veal, often grilled or pan-fried and served with mustard and sauerkraut.', 'Europe', 'Sausage', '$$', 'Mild', 'Pork/Beef/Veal, Spices', 'Grilled/Fried', 4.5),
('churros.jpg', 'Churros', 'Spain', 'A fried-dough pastry, typically sprinkled with sugar and sometimes cinnamon, often served with a side of thick hot chocolate for dipping.', 'Europe', 'Dessert', '$', 'Mild', 'Flour, Sugar, Oil', 'Fried', 4.7);
