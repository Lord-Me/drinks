-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2020 at 12:51 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drinks`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `image` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'professional', 'professional.jpg'),
(2, 'custom', 'custom.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `ingredients` longtext COLLATE utf8_spanish2_ci NOT NULL,
  `content` longtext COLLATE utf8_spanish2_ci NOT NULL,
  `image` varchar(25) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'defaultDrinkImage.jpg',
  `published_at` date NOT NULL DEFAULT current_timestamp(),
  `view` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `author_id`, `category`, `title`, `ingredients`, `content`, `image`, `published_at`, `view`) VALUES
(1, 1, 1, 'Whiskey Old Fashoned', '1 sugar cube\r\n2 to 3 dashes ​Angostura bitters\r\n2 ounces ​bourbon (or rye whiskey)\r\nOptional: orange slice\r\nOptional: splash of club soda\r\nGarnish: ​orange peel\r\nGarnish: maraschino cherry', '1. Place a large ice cube in an old fashioned glass.\r\n2. Add whiskey.\r\n3. Place strainer over your glass and onto it the sugar cube.\r\n4. Saturate the sugar cube with bitters and let it disolve completely.\r\n5. Once disolved, empty any remaining sugar into the glass and stir well.\r\n6. Garnish with an orange peel and maraschino cherry.', 'Old-Fashioned.jpg', '2019-11-04', 1),
(2, 1, 1, 'Long Island Ice Tea', '.75 oz. -or- 22 ml. Lemon Juice\r\n.5 oz. -or- 15 ml. gin\r\n.5 oz. -or- 15 ml. rum\r\n.5 oz. -or- 15 ml. tequila\r\n.5 oz. -or- 15 ml. vodka\r\n.5 oz. -or- 15 ml. Dry Curaçao / triple sec\r\n.25 oz. -or- 8 ml. simple syrup\r\n.75 oz. -or- 22 ml. Coca Cola\r\nGarnish: lemon twist', '1. Pour your fresh lemon juice, simple syrum and different alcohols to your shaker.\r\n2. Add ice, one whole and one cracked, and shake well.\r\n3. Place an ice spike inot a collins glass and pour in the cocktail.\r\n4. Top with fresh coca cola or your own cola syrup.\r\n5. Finally, garnish with a lemon twist and add a straw.', 'Long-Island-Iced-Tea.jpg', '2019-11-04', 1),
(3, 1, 1, 'Irish Coffee', '1 oz. -or- 30 ml. Bushmill\'s Irish Whiskey\r\n~.5 oz. -or- 15 ml. Simple Syrup\r\n3.5 oz. -or- 105 ml. Hot Coffee\r\nWhipped creme\r\nGarnish: grated nutmeg', '1. Prepare a pot of coffee in whichever meets your personal preference.\r\n2. Take your toddy glass and pour in the Whiskey, followed by the simple syrup.\r\n3. Next, add your brewed coffee and stir well.\r\n4. To top, gently float whipped cream over the drink.\r\n5. Finnally, garnish the cream with freshly grated nutmeg.', 'Irish-Coffee.jpg', '2019-11-04', 1),
(4, 6, 1, 'Moscow Mule', 'Crushed ice\r\nCopper mug\r\n2 oz. -or- 60 ml. Vodka\r\n1 oz. -or- 30 ml. Lime Juice\r\n2 oz. -or- 60 ml. Ginger Syrup\r\n~ 5 oz. -or- 150 ml. Seltzer\r\n(OR skip the Ginger Syrup+Seltzer Combo in favour of a spicy\r\nginger beer like Reed\'s)\r\nGarnish: Lime wedge', '1. Fill your copper mug with crushed or cracked ice.\r\n2. Add your Vodka, ginger syrup and lime juice to the mug and stir.\r\n3. Top with seltzer.\r\n4. Add a lime wedge to the rim.', 'Moscow-Mule.jpg', '2019-11-04', 1),
(5, 1, 1, 'Bloody Mary', '2 Barspoons of Lemon Juice\r\nTabasco to Taste\r\nBarspoon of Worcestershire Sauce\r\nHalf Barspoon of Horse Radish\r\n2 oz or 120 ml Vodka\r\n4 oz or 240 ml Tomato Juice\r\nGet Crazy with that Garnish', '1. Mix your lemon juice, Worcestershire Sauce, 3 to 5 dashes of Tabasco sauce and vodka into your glass.\r\n2. Next, add your tomato juice, followed by your horse radish and stir well.\r\n3. Add whole ice and garnish with a stick of celery and assorted pickled delicacies (olive, pickle, etc).', 'Bloody-Mary.jpg', '2019-11-04', 1),
(6, 6, 2, 'Better Title', '~.5 oz. -or- ~ 15 ml. Simple Syrup\r\n2 Dashes Angostura Bitters\r\nA plethora of mint leaves\r\n3 oz. -or- 90 ml. Bourbon\r\nGarnish: several sprigs of mint', '1. Pour your simple syrup into your glass/beaker and drop in mint leaves to your own taste. Here we will use around 18.&#13;&#10;2. Gently muddle the leaves with your muddler&#13;&#10;3. Next, pour in your bourbon.&#13;&#10;4. Now fill the rest of the glass/beaker with ice. Use either crushed ice or shaved ice. If you take the exterior frost seriously, do not touch the sides of your glass/beaker with your fingers.&#13;&#10;5. Next, top with a mound of crushed or shaved ice and pack it down lightly.&#13;&#10;6. Once done, take a large bouquet of mint, give it a gentle spank or two then poke it into the side of the ice mound.&#13;&#10;7. (optional) Add a dash of Jamaican rum over the top.&#13;&#10;8. Finally, slide in your straw close to the bouquet of mint and serve.', 'SmileFace.png', '2019-11-06', 0),
(7, 1, 1, 'Scorpion Bowl', '2 oz or 60 ml Lime Juice\r\n4 oz or 120 ml Orange Juice\r\n1.5 oz or 45 ml Demerara Syrup\r\n2 oz or 60 ml Orgeat\r\n2 oz or 60 ml Brandy\r\n4 oz or 120 ml London Dry Gin\r\n4 oz or 120 ml Aged Rum\r\nShake over Crushed Ice\r\nGarnish with Grated Nutmeg\r\nShare with Friends', '1. Add your lime juice, orange juice, demerara syrup and orgeat into your shaker.\r\n2. Next, pour in your brandy, gin and rum and roll the ingredients by pouring from one container to the other.\r\n3. As it\'t a lot of liquid, pour into two separate shakers, add cracked ice and shake both of them well.\r\n4. Pour both into the scorpion bowl, ice and all and garnish with nutmeg.\r\n5. Optional: if your bowl has a volcano in the centre, place a pure lemon extract soaked cruton there and light it. If not, you can float it on a hollowed out lime half.', 'Scorpion-Bowl.jpg', '2019-11-04', 1),
(8, 6, 2, 'Megaseed Extract - Rick & Morty', 'TONKA BEAN SYRUP\r\n500 grams water\r\n500 grams sugar\r\n10 Tonka beans\r\n\r\nMEGASEED EXTRACT\r\none egg white\r\n.5 oz. -or- 15 ml. Tonka Bean Syrup\r\n.5 oz. -or- 15 ml. Mr. Black Coffee Liqueur\r\n2 oz. -or- 60 ml. Blended Scotch (Monkey Shoulder)\r\nGarnish: Grated Tonka Bean', '0. First you must make yourself a batch of Tonka bean syrup. To do so, blend together 500 grams of water, 500 grams of sugar and 10 tonka beans. Next, transfer to a sauce pan and heat till boiling, then simmer a few minutes. Lastly, let it cool and strain it into a bottle.\r\n\r\n1. Now for the drink. Separate an egg white into your shaker and add your Tonka Bean Syrup, coffee liqueur and blended scotch and dry shake.\r\n2. Next, add ice (one whole, one cracked) and shake well again.\r\n3.Strain into a Coupe glass and very faintly garnish with some grated Tonka bean.\r\n4. (Optional) Letting this drink sit for a moment before serving will allow a thin white head to form. This could be desired for presentation.', 'Megaseed-Extract.jpg', '2019-11-06', 1),
(9, 1, 2, 'Miracle Pill - The Princess Bride', '.5 oz or 15 ml Amaretto\r\n.5 oz or 15 ml Bénédictine\r\n1 oz or 30 ml Bourbon\r\n2.5 oz or 75 ml Cold Brew Coffee\r\n1 egg white\r\nGarnish: Orange Twist & Fresh Grated Dark Chocolate', '1. Pour your amaretto, bénédictine, bourbon, cold coffee and egg white into your shaker and dry shake.\r\n2. Next, add ice (one whole, one cracked) and shake well.\r\n3. Pour into a pre-chilled sherry glass, garnish with an orange twist and freshly grated dark chocolate.', 'Miracle-Pill.jpg', '2019-11-06', 1),
(10, 1, 2, 'The Iron Price - Game Of Thrones', '8 oz or 240 ml Traditional Ale\r\n1 Whole Egg\r\n1 oz or 30 ml Demerara Sryup\r\n1 oz or 30 ml Jamaican Rum\r\n1 oz or 30 ml Peaty Scotch\r\nGarnish: Nutmeg and salt', '1. Pour your ale, egg and demerara syrup into your shaker.\r\n2. Next, pour your Jamaican rum and your peaty scotch of choice into your glass.\r\n3. Next, pierce your yoke then roll by pouring from shaker to shaker.\r\n4. Now comes the dangerous part. Take a raw iron poker and heat it until it\'s red hot then lower it into the drink in your glass.\r\n5. Holding the poker in place, pour in the mixture from your shaker and allow it to heat up with the poker for a short moment before removing.\r\n6. Garnish with freshly grated nutmeg and a pinch of salt.', 'The-Iron-Price.jpg', '2019-11-06', 1),
(11, 6, 2, 'Shade Of The Evening - Game Of Thrones', '5 Sage Leaves\r\nSome Dried Butterfly Pea Blossoms\r\nA Little Liquid Nitrogen\r\n2 oz or 60 ml Batavia Arrack\r\n.5 oz or 15 ml Maurin Quina\r\n.5 oz or 15 ml Demerara Syrup\r\n.5 oz or 15 ml Gum Syrup', '1. Drop your sage leaves and your dried butterfly pea blossoms into your shaker.\r\n\r\n2. WARNING: Liquid nitrogen can be dangerous. Research safety precautions before use.\r\nNext, CAREFULLY pour a dash of liquid nitrogen into your shaker and muddle into a powder.\r\n3. Next, pour in your batavia arrack, maurin quina, demerara syrup and gum syrup for thickness.\r\n4. (optional) Pour a little amount of absince into your glass. Just enough for a rinse. Then pour a little liqid nitrogen into your glass to chill and show off some more.\r\n5. Add ice to your shaker (one whole and one cracked) and shake well.\r\n6. Double strain your drink into your glass and float a single spanked sage leaf to garnish.', 'Shade-Of-The-Evening.jpg', '2019-11-06', 1),
(12, 6, 2, 'Arbor Gold - Game Of Thrones', 'St. Germaine to rinse\r\n.5 oz. -or- 15ml. simple syrup\r\n2 barspoons benedictine\r\n1.5 oz. -or- 45 ml. Cognac\r\nGarnish: Rosemary', '1. Pour a small amount of St. Germanie into your glass and swirl it around to coat the sides before disposing of.\r\n2. To start the mix, pour your simple syrup, benedictine and cognac to a container.\r\n3. Add cracked ice to your container and stir.\r\n4. Pour the drink into your glass and garnish with a stick of rosemary.', 'Arbor-Gold.jpg', '2019-11-06', 1),
(13, 6, 2, 'Milk Of The Poppy - Game Of Thrones', 'POPPY SEED ORGEAT\r\n• 200 grams of Poppy Seed\r\n• 200 Grams of Sugar\r\n• 200 Grams of Water\r\n\r\nMILK OF THE POPPY\r\n• .75 oz. -or- 22 ml. Poppy Orgeat\r\n• .5 oz. -or- 15 ml. Dry Curaçao\r\n• 2 oz. -or- 60 ml. Cognac\r\n• Rinse Glass in Absinthe\r\n• Shake over ice and double strain', '0. Before you begin, make yourself a batch of poppy seed orgeat: Add 200 grams of poppy seeds, 200 grams of sugar and 200 grams of water into your blender and pulverise. Let it sit for an hour. If the sugar has not completely dissolved, transfer it to a pan and simmer until it has dissolved. Lastly, strain and bottle your orgeat.\r\n\r\n1. Pour your poppy orgeat, dry curaçao and cognac into your shaker.\r\n2. (Optional) Rince your glass with a dash of absinthe.\r\n3. Add ice and shake well.\r\n4. Pour into your glass and enjoy.', 'Milk-Of-The-Poppy.jpg', '2019-11-06', 1),
(17, 6, 1, 'tewarer', 'esare', 'esaresa', 'defaultDrinkImage.jpg', '2019-12-13', 0),
(18, 6, 1, 'Test', 'fdsafdsa&#13;&#10;dsfsda', 'dsafdsafdsa&#13;&#10;dsafdsa', 'originalDrinks.jpg', '2019-12-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(20) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `province` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `avatar` varchar(50) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'defaultAvatar.png',
  `role` int(11) NOT NULL DEFAULT 2,
  `language` varchar(2) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `province`, `username`, `email`, `password`, `avatar`, `role`, `language`) VALUES
(1, 'admin', 'SR', 'Province', 'admin', 'admin@drinks.com', '$2y$12$K1lggcRKGKv6txa5GfokG.3VydiMlj56tuRp6MOP5CFaBpl8qou7u', 'defaultAvatar.png', 1, 'en'),
(6, 'Vicent', 'Jorda', 'Pego', 'dwes', 'dwes@drinks.com', '$2y$12$po6HvhxwAxIwHRLuBqeaOOjCGhJrDZhT0KktgCjpX/WHBlDx/Ep9i', 'SmileFace.png', 2, 'en'),
(9, 'Aidan', 'Pennell', 'Valencia', 'aidPennell', 'ajpennell@gmail.com', '$2y$10$5m2wsf/QzAYd2VG4tE4gUeilunCO11qAqllbaNA0.9VIvfThpqIWa', 'defaultAvatar.png', 2, 'en'),
(12, 'John', 'Pennell', 'Valencia', 'johnPennell', 'johnPennell@gmail.com', '$2y$10$2XksQzQu8dTpusrWSF3IaeyN7GkNlY9JD.nXT5A79ryQTfsYhDcFS', 'defaultAvatar.png', 2, 'en');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK` (`author_id`),
  ADD KEY `category-category_id` (`category`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `author_id-user_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `category-category_id` FOREIGN KEY (`category`) REFERENCES `categories` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
