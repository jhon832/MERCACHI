-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-11-2024 a las 23:07:12
-- Versión del servidor: 8.0.17
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `supermercadodb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `categoria`, `imagen`, `descripcion`) VALUES
(148, 'Agua Mineral', 1500.00, 'Bebidas', 'https://static.merqueo.com/images/products/large/f6fc3d4f-e634-4743-a582-3d65cb925202.jpg', 'Agua pura y refrescante'),
(149, 'CocaCola', 2500.00, 'Bebidas', 'https://tb-static.uber.com/prod/image-proc/processed_images/117bf5724c43e279c31ecb46e80ef4b4/957777de4e8d7439bef56daddbfae227.jpeg', 'Bebida gaseosa con sabor a cola'),
(150, 'Jugo de Naranja', 2000.00, 'Bebidas', 'https://m.media-amazon.com/images/I/41QtxymW5xL.jpg', '100% jugo natural de naranja'),
(151, 'Té Helado', 2000.00, 'Bebidas', 'https://t1.uc.ltmcdn.com/es/posts/5/8/3/como_preparar_te_helado_15385_orig.jpg', 'Té negro con un toque de limón'),
(152, 'Cerveza Artesanal', 2500.00, 'Bebidas', 'https://imagenes.elpais.com/resizer/v2/6RWBVMD53ZAJDFKUH5JSRW5GTE.jpg?auth=cfab58f79eaab39f949dde6d1dc4332e85ff65babbd7b79bee6a0dbece9853d9&width=1200', 'Cerveza local con sabor único'),
(153, 'Limonada', 1500.00, 'Bebidas', 'https://s3.ppllstatics.com/diariosur/www/pre2017/multimedia/noticias/201602/16/media/cortadas/limonada._xoptimizadax--490x578.jpg', 'Refrescante bebida de limón natural'),
(154, 'Tomates', 4500.00, 'Verduras', 'https://png.pngtree.com/png-vector/20210529/ourmid/pngtree-tomato-plant-red-vegetables-png-image_3369377.jpg', 'Frescos y llenos de sabor'),
(155, 'Zanahorias', 8000.00, 'Verduras', 'https://img.freepik.com/foto-gratis/imagen-realista-monton-zanahorias-sobre-fondo-colorido_125540-3798.jpg', 'Ricas en vitamina A'),
(156, 'Pimientos', 8000.00, 'Verduras', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/6c6c7d34-8924-5e84-ae84-979b2ffc7562/156432d4-bf93-5c25-88ff-d5a308e616e6.jpg', 'Colores variados y crujientes'),
(157, 'Brócoli', 9500.00, 'Verduras', 'https://plus.unsplash.com/premium_photo-1724250160975-6c789dbfdc9f?fm=jpg&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fGJyb2Njb2xpfGVufDB8fDB8fHww&ixlib=rb-4.0.3&q=60&w=3000', 'Ideal para ensaladas y guisos'),
(158, 'Espinacas', 6000.00, 'Verduras', 'https://www.conasi.eu/blog/wp-content/uploads/2023/07/recetas-con-espinacas-1.jpg', 'Hojas verdes ricas en hierro'),
(159, 'Papas', 7500.00, 'Verduras', 'https://images.cookforyourlife.org/wp-content/uploads/2018/08/Roasted-Potatoes-e1443469101276.jpg', 'Versátiles y nutritivas'),
(160, 'Manzanas', 2500.00, 'Frutas', 'https://img.freepik.com/psd-gratis/cerca-manzana-aislada_23-2151598148.jpg', 'Manzanas frescas y crujientes'),
(161, 'Plátanos', 1800.00, 'Frutas', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/40680746-539b-5265-84e4-d10a1bdcd160/fdc560f6-4d25-5239-88e8-666a41f1a0c6.jpg', 'Plátanos maduros y dulces'),
(162, 'Naranjas', 2200.00, 'Frutas', 'https://www.ouinolanguages.com/thumb/Spanish/vocab/12/images/pic12.jpg', 'Naranjas jugosas y llenas de vitamina C'),
(163, 'Fresas', 3500.00, 'Frutas', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/45c07b8e-5053-5353-bd81-57fb2509f31a/b9c42c2f-ef19-5acc-9787-f416a49c06bc.jpg', 'Fresas rojas y aromáticas'),
(164, 'Uvas', 4000.00, 'Frutas', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/cf387232-57a6-592a-8023-a5f2f7541e73/428cf7a2-6f52-5125-bb0a-50a781b3f331.jpg', 'Racimos de uvas verdes y moradas'),
(165, 'Piña', 3200.00, 'Frutas', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/3587d4fe-c344-5d5a-9c93-66e095381f55/19fff8e5-c8d2-5bcc-b34c-43e2ef28b80a.jpg', 'Piña tropical dulce y refrescante'),
(166, 'Arroz Blanco', 2500.00, 'Granos', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/c1fba3bc-afe4-5090-96fc-6c646658534f/33e51ad1-1dc1-56f7-855e-363942bda5ad.jpg', 'Grano largo de alta calidad'),
(167, 'Frijoles Negros', 3200.00, 'Granos', 'https://media.gettyimages.com/id/1205070106/es/foto/overhead-view-of-spoon-and-bowl-of-black-bean-on-wooden-table.jpg?s=612x612&w=0&k=20&c=9Sc8IpbNX0SBrMfLg3wfWPSpgj_NGDsc38Ni9KLJlpU=', 'Ricos en proteínas y fibra'),
(168, 'Lentejas', 2800.00, 'Granos', 'https://media.gettyimages.com/id/186804026/es/foto/usan-las-lentejas-y-la-cuchara.jpg?s=612x612&w=0&k=20&c=-Fv5c9YdrhC1OSoExGPCQfQ7EK2JizmGhJnbjsZirNw=', 'Nutritivas y versátiles'),
(169, 'Garbanzos', 3500.00, 'Granos', 'https://media.gettyimages.com/id/136239617/es/foto/chick-guisantes.jpg?s=612x612&w=0&k=20&c=LuS5GQjYFahaEPbCIj30YhOp_Y0eIpd6_ubFa9La4as=', 'Ideales para hummus y ensaladas'),
(170, 'Quinoa', 6000.00, 'Granos', 'https://media.gettyimages.com/id/171320698/es/foto/quinua.jpg?s=612x612&w=0&k=20&c=-BbwRMLpYty5djJ38rp9AOyYxZp2zALJD8kbOegJMmc=', 'Superalimento rico en proteínas'),
(171, 'Maíz para Palomitas', 3000.00, 'Granos', 'https://media.gettyimages.com/id/171374946/es/foto/y-las-palomitas-de-ma%C3%ADz.jpg?s=612x612&w=0&k=20&c=nKDMIAVy9aXozzvgiszavxSZHwHx3v6QgLHKhlY-JKg=', 'Granos seleccionados de alta calidad'),
(172, 'Jabón de Manos', 3500.00, 'Higiene', 'https://www.protex-soap.com/content/dam/cp-sites/personal-care/protex-relaunch/latam/products/jabon-liquido-avena-prebiotico-221ml.jpg', 'Suave y antibacterial'),
(173, 'Champú Anticaspa', 12000.00, 'Higiene', 'https://m.media-amazon.com/images/I/61ehqViIK2L.jpg', 'Limpieza profunda del cuero cabelludo'),
(174, 'Pasta de Dientes Blanqueadora', 8000.00, 'Higiene', 'https://m.media-amazon.com/images/I/61iyUw9eSvL._AC_UF1000,1000_QL80_.jpg', 'Para una sonrisa brillante'),
(175, 'Desodorante Roll-on', 7500.00, 'Higiene', 'https://m.media-amazon.com/images/I/91sMxlpXLBL._AC_UF1000,1000_QL80_.jpg', 'Protección 24 horas'),
(176, 'Gel de Ducha', 9000.00, 'Higiene', 'https://cdn.shopify.com/s/files/1/0563/4375/6869/products/63554f6-0efbed9-lavanda_dim2.png?height=512&width=512', 'Con aroma refrescante'),
(177, 'Cepillo de Dientes', 5000.00, 'Higiene', 'https://m.media-amazon.com/images/I/51hSzh1H+8L._AC_UF1000,1000_QL80_.jpg', 'Cerdas suaves y ergonómico'),
(178, 'Filete de Res', 30000.00, 'Carnes', 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/0ea00d09-3d2c-5a3c-9465-3e68ecba8775/d2b61220-bbf7-5730-80b7-df6602eec9e4.jpg', 'Corte tierno y jugoso de carne de res'),
(179, 'Chuletas de Cerdo', 20000.00, 'Carnes', 'https://progcarne.com/storage/app/uploads/public/603/ebd/dac/603ebddac3c1a274493475.jpg', 'Sabrosas chuletas de cerdo para la parrilla'),
(180, 'Pechuga de Pollo', 12000.00, 'Carnes', 'https://clientes.sigmafoodservice.com/medias/70060023.jpg?context=bWFzdGVyfGltYWdlc3w2MDYzOXxpbWFnZS9qcGVnfGFXMWhaMlZ6TDJnd1lpOW9aRE12T1RJMk9EQXhNVFF6TkRBeE5DNXFjR2N8YzM1MGQ5YzQ3ZTA4Y2Y3NjZkNWQ3ZThlMmVkZDYxZDE0OTE2YjRlYTI4YjMwNmI1YjZkYmFkZTVmZTliYWRmMQ', 'Carne blanca magra y versátil'),
(181, 'Cordero', 35000.00, 'Carnes', 'https://hips.hearstapps.com/hmg-prod/images/cordero-deshuesado-con-setas-1581523076.jpg?crop=1xw%3A1xh%3Bcenter%2Ctop&resize=980%3A%2A', 'Carne tierna con sabor distintivo'),
(182, 'Lomo de Ternera', 38000.00, 'Carnes', 'https://i.blogs.es/0566a7/solomillo/450_1000.jpg', 'Corte suave y delicado de ternera joven'),
(183, 'Costillas de Res', 25000.00, 'Carnes', 'https://comedera.com/wp-content/uploads/sites/9/2023/09/shutterstock_389651584.jpg', 'Jugosas costillas para asar lentamente'),
(184, 'Chocolate con Leche', 2000.00, 'Dulces', 'https://image.slidesdocs.com/responsive-images/background/milk-cream-splashing-amidst-a-shower-of-chocolate-pieces-in-3d-illustration-powerpoint-background_a6cfeb9011__960_540.jpg', 'Delicioso chocolate cremoso'),
(185, 'Caramelos Surtidos', 2250.00, 'Dulces', 'https://images.unsplash.com/photo-1601493701002-3223e7e1ebaf?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Variedad de sabores frutales'),
(186, 'Gomitas de Osito', 500.00, 'Dulces', 'https://cdn.pixabay.com/photo/2024/04/19/17/39/ai-generated-8706896_960_720.png', 'Suaves y masticables'),
(187, 'Paleta de Caramelo', 1200.00, 'Dulces', 'https://img.freepik.com/foto-gratis/vista-superior-paletas-colores-junto-caramelos-blanco-azucar-arco-iris-colores_140725-24965.jpg', 'Gran variedad de sabores'),
(188, 'Bombones Rellenos', 2000.00, 'Dulces', 'https://content-cocina.lecturas.com/medio/2021/01/04/chocovasitos_de_frutos_secos_y_bombones_rellenos_de_caramelo_06101458_800x800.jpg', 'Deliciosos y rellenitos'),
(189, 'Chicles de Menta', 700.00, 'Dulces', 'https://mundodulces17.com/wp-content/uploads/2023/09/D_NQ_NP_779367-MCO51875725132_102022-O.webp', 'Refrescantes y duraderos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `contraseña`, `fecha_registro`) VALUES
(14, 'stiven@gmail.com', '$2y$10$WtnzLWxrYcCRkHVfGM1nOeoP9lKhWpd8sQm75SClkFGmCWGaVTUAq', '2024-11-21 19:06:57'),
(15, 'jhon@gmail.com', '$2y$10$bcdsFBK3BaycEvj/y7aox.j8VJ5LdVfEF8W9MOA.utrwLK/MPc./S', '2024-11-22 00:05:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`usuario_id`,`producto_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
