-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2025 a las 13:17:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cubestore`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo`
--

CREATE TABLE `catalogo` (
  `id_Producto` int(11) NOT NULL,
  `NomProducto` varchar(100) NOT NULL,
  `DesProducto` text DEFAULT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `CatEdad` varchar(50) DEFAULT NULL,
  `ImgProducto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogo`
--

INSERT INTO `catalogo` (`id_Producto`, `NomProducto`, `DesProducto`, `Precio`, `CatEdad`, `ImgProducto`) VALUES
(1, 'Gimnasio Fisher Price', 'Divertido gimnasio básico para bebés.', 900.00, 'Para Bebés (0-2 años)', 'img/1.jpg'),
(2, 'Mordedera Sophie…', 'Entrenador bucal para bebés, ideal para la dentición.', 450.00, 'Para Bebés (0-2 años)', 'img/2.jpg'),
(3, 'Bloques apilables Tela…', 'Pequeños bloques de tela para estimular la creatividad de los niños.', 400.00, 'Para Bebés (0-2 años)', 'img/3.jpg'),
(4, 'Juguete de arrastre', 'Divertido cachorro de madera para facilitar el caminar en los primeros años del niño.', 600.00, 'Para Bebés (0-2 años)', 'img/4.jpg'),
(5, 'Libros interactivos', 'Libros con sonidos y texturas para momentos de aprendizaje visual y auditivo.', 450.00, 'Para Bebés (0-2 años)', 'img/5.jpg'),
(6, 'Pelota sensorial', 'Cómoda y suave pelota con diferentes texturas para estimular los sentidos del bebé.', 250.00, 'Para Bebés (0-2 años)', 'img/6.jpg'),
(7, 'Mesa de actividades bilingüe', 'Mesa de aprendizaje interactiva con luces y sonidos en español e inglés.', 1500.00, 'Para Bebés (0-2 años)', 'img/7.jpg'),
(8, 'Andadera aprendizaje', 'Andadera entrenadora 2 en 1 con panel de actividades para la caminata del bebé.', 800.00, 'Para Bebés (0-2 años)', 'img/8.jpg'),
(9, 'Juguetes para el baño', 'Set de juguetes flotantes para que el momento del baño sea más divertido.', 250.00, 'Para Bebés (0-2 años)', 'img/9.jpg'),
(10, 'Piano pataditas', 'Gimnasio y piano para bebés que se activa con los pies al estar acostado.', 1000.00, 'Para Bebés (0-2 años)', 'img/10.jpg'),
(11, 'Muñecas L.O.L', 'Pequeñas muñecas miniaturas de colección con sorpresas y accesorios.', 350.00, 'Infantes', 'img/11.jpg'),
(12, 'Set LEGO friends', 'Set de construcción LEGO Friends con múltiples accesorios y mini figuras.', 600.00, 'Infantes', 'img/12.jpg'),
(13, 'Barbie Dreamhouse', 'Casa de Barbie de varios pisos con muchas habitaciones para horas de juego.', 4000.00, 'Infantes', 'img/13.jpg'),
(14, 'Fábrica de pasteles', 'Set de masa moldeable Play-Doh para crear pasteles y repostería.', 450.00, 'Infantes', 'img/14.jpg'),
(15, 'Sets de arte Crayola', 'Set básico de arte con crayones, plumones y colores para artistas principiantes.', 600.00, 'Infantes', 'img/15.jpg'),
(16, 'Bicicleta', 'Bicicleta básica rodada 16, ideal para el transporte y juego de niños.', 1500.00, 'Infantes', 'img/16.jpg'),
(17, 'Cocinita de juguete', 'Cocina interactiva con accesorios para simulación de arte culinario.', 550.00, 'Infantes', 'img/17.jpg'),
(18, 'My Little Pony', 'Bonitas figuras coleccionables de los personajes de la serie My Little Pony.', 200.00, 'Infantes', 'img/18.jpg'),
(19, 'Nenuco doctora', 'Muñeca Nenuco con accesorios de doctora para jugar al cuidado del bebé.', 750.00, 'Infantes', 'img/19.jpg'),
(20, 'Mascotas interactivas', 'Mascota de peluche interactiva con acciones y sonidos básicos.', 800.00, 'Infantes', 'img/20.jpg'),
(21, 'Pista Hot Wheels', 'Magnífica pista de autos a escala con lanzadores y giros.', 1600.00, 'Infantes', 'img/21.jpg'),
(22, 'Figuras de acción Marvel o DC', 'Tus superhéroes favoritos de 6 pulgadas con puntos de articulación.', 300.00, 'Infantes', 'img/22.jpg'),
(23, 'Sets LEGO city', 'Set de construcción LEGO City con múltiples escenarios de la ciudad.', 700.00, 'Infantes', 'img/23.jpg'),
(24, 'Lanzadores Nerf', 'Lanzadores de dardos de espuma para juego activo y seguro.', 250.00, 'Infantes', 'img/24.jpg'),
(25, 'Carro a control remoto', 'Pequeño auto a escala, controlado con un mando a distancia.', 500.00, 'Infantes', 'img/25.jpg'),
(26, 'Dinos Jurassic World', 'Dinosaurio de plástico articulado para la recreación de la película.', 450.00, 'Infantes', 'img/26.jpg'),
(27, 'Max Steel', 'Figura de acción de Max Steel con su compañero robot Steel.', 250.00, 'Infantes', 'img/27.jpg'),
(28, 'Figuras y torre Paw Patrol', 'Centro de mando de Paw Patrol con figuras y vehículos de cachorros.', 2500.00, 'Infantes', 'img/28.jpg'),
(29, 'Rompecabezas Novelty', 'Juego de mesa para la destreza mental, de 300 piezas.', 150.00, 'Infantes', 'img/29.jpg'),
(30, 'Transformers', 'Figura de Optimus Prime, el héroe transformer de la caricatura, convertible.', 600.00, 'Infantes', 'img/30.jpg'),
(31, 'Juego de química', 'Set de experimentos científicos para aprender química de forma segura y divertida.', 400.00, 'Para Niños y Niñas (General)', 'img/31.jpg'),
(32, 'Turista Mundial', 'Juego de mesa familiar que te permite adquirir propiedades por el mundo.', 140.00, 'Para Niños y Niñas (General)', 'img/32.jpg'),
(33, 'Slime o arena cinética', 'Juguetes sensoriales para el desestrés, con textura relajante.', 150.00, 'Para Niños y Niñas (General)', 'img/33.jpg'),
(34, 'Cubo de rubik', 'Cubo rompecabezas clásico 3x3 para medir tu destreza e inteligencia.', 200.00, 'Para Niños y Niñas (General)', 'img/34.jpg'),
(35, 'Jenga', 'Juego de habilidad de torre de bloques de madera para toda la familia.', 300.00, 'Para Niños y Niñas (General)', 'img/35.jpg'),
(36, 'Pizarra mágica', 'Pizarra para dibujo o escritura que se borra fácilmente.', 200.00, 'Para Niños y Niñas (General)', 'img/36.jpg'),
(37, 'Montables eléctricos', 'Vehículo montable eléctrico para que el niño conduzca de forma segura.', 1500.00, 'Para Niños y Niñas (General)', 'img/37.jpg'),
(38, 'Trampolín', 'Trampolín pequeño con red de seguridad para saltar y divertirse.', 1800.00, 'Para Niños y Niñas (General)', 'img/38.jpg'),
(39, 'Karaoke infantil', 'Set de micrófono, base y bocina para karaoke, con conexión Bluetooth.', 500.00, 'Para Niños y Niñas (General)', 'img/39.jpg'),
(40, 'Set de magia', 'Set con trucos de magia para sorprender a familiares y amigos.', 350.00, 'Para Niños y Niñas (General)', 'img/40.jpg'),
(41, 'Juego de mesa Catán', 'Juego de mesa de estrategia sobre comercio y colonización de una isla.', 750.00, 'Para Jóvenes Adultos', 'img/41.jpg'),
(42, 'Dron con cámara', 'Dron volador con cámara integrada para capturar vistas aéreas.', 6500.00, 'Para Jóvenes Adultos', 'img/42.jpg'),
(43, 'Videojuegos para consola', 'Videojuegos de última generación para consolas PS5, Xbox o Nintendo Switch.', 1200.00, 'Para Jóvenes Adultos', 'img/43.jpg'),
(44, 'Juegos de cartas', 'Juego de cartas interactivas como UNO o similares para el desarrollo social.', 250.00, 'Para Jóvenes Adultos', 'img/44.jpg'),
(45, 'Rompecabezas', 'Rompecabezas para la destreza mental con 1,000 piezas y paisajes.', 350.00, 'Para Jóvenes Adultos', 'img/45.jpg'),
(46, 'LEGO Technic', 'Set de construcción avanzada de LEGO Technic, pieza de colección armable.', 6500.00, 'Para Jóvenes Adultos', 'img/46.jpg'),
(47, 'Monopoly', 'Juego de mesa clásico donde se adquieren propiedades y se aprende sobre finanzas.', 400.00, 'Para Jóvenes Adultos', 'img/47.jpg'),
(48, 'Funko Pop!', 'Figura coleccionable de vinilo con estilo de arte Pop sobre personajes de moda.', 250.00, 'Para Jóvenes Adultos', 'img/48.jpg'),
(49, 'Set de Dungeon & Dragons', 'Caja de inicio para jugadores de Dungeon & Dragons, juego de rol de mesa.', 450.00, 'Para Jóvenes Adultos', 'img/49.jpg'),
(50, 'Juego de escape en caja', 'Juego de destreza y lógica tipo \"escape room\" para varias personas.', 300.00, 'Para Jóvenes Adultos', 'img/50.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_nombre` varchar(255) NOT NULL,
  `producto_precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `cp` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id`, `usuario_id`, `calle`, `ciudad`, `estado`, `cp`, `fecha_registro`) VALUES
(1, 14, 'Tu Mama Numero 5', 'Estado de Mexico', 'Cuautitlan Izcalli', '543566', '2025-10-22 10:43:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `direccion_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `envio` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `direccion_id`, `subtotal`, `envio`, `iva`, `total`, `fecha`) VALUES
(1, 14, 1, 3050.00, 50.00, 488.00, 3588.00, '2025-10-22 11:15:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `contrasena`, `telefono`, `fecha_registro`) VALUES
(2, 'Deylan', 'Ortiz', 'Gamaliel', 'holasoyyo@gmail.com', '$2y$10$2BMukoP.9/NbMHW.a1QlouZ/ZJV6a9siD3oXLz5/2dYr/azvQBgMK', '5521342332', '2025-10-17 22:47:47'),
(3, 'Yael Roman', 'Reyes', 'Rendon', 'zombie@gmail.com', '$2y$10$A/VvFcx/7v29FMxEaMuxgORtylqtxMRqGuVoHea7dLllnHHMrYksu', '213241255', '2025-10-17 22:49:50'),
(4, 'Leonel', 'Reyes', 'Rendon', 'zombi@gmail.com', '$2y$10$gJ31TPxyiN9VffN89bwjt.cS4c0.CR4ufEAH5KY0hwGfcWmibLKh.', '213124422', '2025-10-17 22:51:10'),
(5, 'Yael Roman', 'Reyes', 'Rendon', 'yomero@gmail.com', '$2y$10$Kib0Gm87fhI.YNyGw6nFe.cJSVqS4IG5z49UkgKroXR7rRUNNiuK6', '5587382482', '2025-10-17 22:55:38'),
(6, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'froigama@gmail.com', '$2y$10$xUhHBmLGN9NJzFusQS2r8O4.02T4d6598jvhFm.9IP3GbXcJ/HteO', '5628381400', '2025-10-20 21:58:31'),
(7, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'kidxz@mail.com', '$2y$10$dRdpTXqvr62ckIAfoc0wr.LEk5xjQSTLgPvYfjWujzbCmrc0IaZEa', '56283814', '2025-10-20 22:00:54'),
(8, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'asdas@gasd', '$2y$10$q0.vg7RiOGQBWFZA.BZGZeYuIIncC4sqO01IFMiXEkw1mm5OFdfMC', '5628381400', '2025-10-20 22:02:39'),
(9, 'asd', 'asd', 'asdds', 'asd@asd.com', '$2y$10$8evLrJ0u7I9Xb7i/sN2IAuvArEj4aAJrr5fm.VxN2PVOa/ICXCbwC', '6546546', '2025-10-21 03:56:29'),
(10, 'asdasd', 'asdasdas', 'asdasd', '654654@asdasd.com', '$2y$10$H6oUeg9oOW3IO/7Easuoy.TxL4spetg8wTnyFJrl8kP.XqYDa2Bxu', '654654', '2025-10-21 03:58:42'),
(11, 'FROILAN GAMALIEL', 'aguilar', 'gamero', '65464@asdasd.com', '$2y$10$6sFgXo0mtKKk1LZYU0d.eevtXPa1QYFGbxVt7s0ba3autQl//Q9wu', '5628381400', '2025-10-21 04:00:01'),
(12, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'asd@as.com', '$2y$10$8d/uw8dc1jVe.TalM20f0Osgq57o2gwuVmttIvlYqO.SFzNqxLdaK', '5628381400', '2025-10-21 04:04:22'),
(13, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'abc@abc.com', '$2y$10$40bFGuO3RQOLuCm63eOZRu1y6irxSasw0JeqN3ERPAc8Y.DpZBvFu', '5628381400', '2025-10-21 04:06:26'),
(14, 'FROILAN GAMALIEL', 'aguilar', 'gamero', 'abc@abcd.com', '$2y$10$Hh2sfD8OpNGPXj8mUFo28.yFuSIekcIVuvg7Q1Zdl8ccKuaFOctfe', '5628381400', '2025-10-21 06:58:29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  ADD PRIMARY KEY (`id_Producto`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `direccion_id` (`direccion_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `catalogo`
--
ALTER TABLE `catalogo`
  MODIFY `id_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
