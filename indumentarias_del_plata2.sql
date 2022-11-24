drop database if exists indumentarias_del_plata2;
create database indumentarias_del_plata2;
use indumentarias_del_plata2;

/********************************SENTENCIAS DE CREACIÓN DE TABLAS*************************/
/*SENTENCIAS PARA TABLA PERSONA*/
create table persona(
ci int primary key,
nombre varchar(24) not null,
apellido varchar(30) not null,
fecha_nac date not null,
ciudad varchar(50),
calle1 varchar(50),
calle2 varchar(50),
calle3 varchar(50),
nro varchar(50),
correo varchar(320) not null
);

/*SENTENCIAS PARA TABLA TEL*/
create table tel(
ci int not null,
num varchar(9) primary key,
constraint tel_ci_fk foreign key (ci) references persona (ci)
);

/*SENTENCIAS PARA TABLA USUARIO*/
create table usuario(
nomb_usu varchar(16) primary key,
pass varchar(225) not null,
ci int not null,
tipo varchar(8) not null check(tipo = "adm" OR tipo="empleado" OR tipo="cliente"),
suspendido bool not null,
constraint usr_ci_fk foreign key (ci) references persona (ci)
);

/*SENTENCIAS PARA TABLA CLIENTE*/
create table cliente(
nomb_usu varchar(16) primary key,
constraint cli_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu) on delete cascade
);

/*SENTENCIAS PARA TABLA PRODUCTO*/
create table producto(
id_prod int primary key auto_increment,
nomb_prod varchar(40) not null,
precio decimal(7,2) not null,
categoria varchar(50) not null,
genero varchar(30) check(genero="H" OR genero="M" OR genero="*"),
subcategoria varchar(100),
marca varchar(30) not null,
público bool not null,
stock int not null,
descuento int not null,
descripcion varchar(500) not null,
comprados int not null,
img varchar(300) not null,
min_stock int not null
);

/*SENTENCIAS PARA TABLA BUSQUEDA*/
create table busqueda(
nomb_usu varchar(16),
id_prod int,
fecha date not null,
constraint nomb_usu_id_prod_pk primary key (nomb_usu, id_prod),
constraint busqueda_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu),
constraint busqueda_id_prod_fk foreign key (id_prod) references producto (id_prod) on delete cascade
);

/*SENTENCIAS PARA TABLA GUARDA*/
create table guarda(
nomb_usu varchar(16),
id_prod int,
cantidad int not null,
constraint nomb_usu_id_prod_pk primary key (nomb_usu, id_prod),
constraint guarda_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu),
constraint guarda_id_prod_fk foreign key (id_prod) references producto (id_prod) on delete cascade
);

/*SENTENCIAS PARA TABLA ADM*/
create table adm(
nomb_usu varchar(16) primary key,
constraint adm_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu) on delete cascade
);

/*SENTENCIAS PARA TABLA EMPLEADO*/
create table empleado(
nomb_usu varchar(16) primary key,
constraint emp_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu) on delete cascade
);

/*SENTENCIAS PARA TABLA COMPRA*/
create table compra(
id_compra int primary key auto_increment,
fecha date not null,
monto decimal(7,2) not null,
nomb_usu varchar(16) not null,
constraint com_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu)
);

/*SENTENCIAS PARA TABLA TIENE (compra_tiene_producto)*/
create table tiene(
id_compra int not null,
id_prod int not null,
cantidad int not null,
constraint id_compra_id_prod_pk primary key (id_compra, id_prod),
constraint tiene_id_compra_fk foreign key (id_compra) references compra (id_compra),
constraint tiene_id_prod_fk foreign key (id_prod) references producto (id_prod)
);

/*SENTENCIAS PARA TABLA CARGA (administrador_carga_producto)*/
create table carga(
nomb_usu varchar(16) not null,
id_prod int primary key,
fecha date not null,
constraint ap_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu),
constraint ap_id_prod_fk foreign key (id_prod) references producto (id_prod) on delete cascade
);

/*SENTENCIAS PARA TABLA GESTIONA (empleado_gestiona_producto)*/
create table gestiona(
id_gestion int primary key auto_increment,
fecha date not null,
info varchar(200) null,
accion varchar(200) not null check(accion="Agregó stock" OR accion="Quitó stock" OR accion="Publicó" OR accion="Ocultó"),
nomb_usu varchar(16) not null,
id_prod int not null,
constraint gest_nomb_usu_fk foreign key (nomb_usu) references usuario (nomb_usu),
constraint gest_id_prod_fk foreign key (id_prod) references producto (id_prod) on delete cascade
);

/********************************SENTENCIAS DE CARGA DE REGISTROS*************************/
/*REGISTROS PARA PERSONA*/
insert into persona values(55002508, 'Manuel', 'Maquioli', '2004-7-27', 'Parque del Plata', 'S1', '12', '14', null, 'manumaquioli@gmail.com');
insert into persona values(39969685, 'Daniela', 'Odriosola', '1975-11-09', 'Parque del Plata', 'S1', '12', '14', null, 'daniodriosola@gmail.com');
insert into persona values(55257579, 'Valentin', 'Cecilio', '2003-2-3', 'Parque del Plata', null, null, null, null, 'pipo@gmail.com');
insert into persona values(54747832, 'Camilo', 'González', '2003-12-24', 'Floresta', null, null, null, null, 'camilogonzalez@gmail.com');
insert into persona values(54608321, 'Pío', 'Garetto', '2003-10-21', 'Costa Azul', null, null, null, null, 'piogaretto@gmail.com');

/*REGISTROS PARA TEL*/
insert into tel values(55002508, '092675250');
insert into tel values(39969685, '099324105');
insert into tel values(55257579, '092084391');
insert into tel values(54747832, '098594594');
insert into tel values(54608321, '099093709');

/*REGISTROS PARA USUARIO*/
insert into usuario values('manuADM', '$2y$10$60pCFi3wWV7p032acqGypOTwJnRSWAot5l81ZYViaaB/ePR8qm7eS', 55002508, 'adm', 0);
/*contraseña: manu12345*/
insert into usuario values('manuEMP', '$2y$10$60pCFi3wWV7p032acqGypOTwJnRSWAot5l81ZYViaaB/ePR8qm7eS', 55002508, 'empleado', 0);
/*contraseña: 12345*/
insert into usuario values('manuCLI', '$2y$10$60pCFi3wWV7p032acqGypOTwJnRSWAot5l81ZYViaaB/ePR8qm7eS', 55002508, 'cliente', 0);
/*contraseña: 12345*/

insert into usuario values('daniela_user_adm', '$2y$10$crL4xV9JOVN8eMciCRpsvOASxuXivj9/xjk61YNsE9UhmROrIkmEC', 39969685, 'adm', 0);
/*contraseña: daniela12345*/
insert into usuario values('daniela_user_emp', '$2y$10$crL4xV9JOVN8eMciCRpsvOASxuXivj9/xjk61YNsE9UhmROrIkmEC', 39969685, 'empleado', 0);
/*contraseña: daniela12345*/
insert into usuario values('daniela_user', '$2y$10$crL4xV9JOVN8eMciCRpsvOASxuXivj9/xjk61YNsE9UhmROrIkmEC', 39969685, 'cliente', 0);
/*contraseña: daniela12345*/

insert into usuario values('pipoADM', '$2y$10$Iu90dJv/ay/YyjG3aepwhuyKZV2r7U.iOpCHNqoajpYlauU.UB2AW', 55257579, 'adm', 0);
/*contraseña: pipo12345*/
insert into usuario values('pipoEMP', '$2y$10$Iu90dJv/ay/YyjG3aepwhuyKZV2r7U.iOpCHNqoajpYlauU.UB2AW', 55257579, 'empleado', 0);
/*contraseña: pipo12345*/
insert into usuario values('pipoCLI', '$2y$10$Iu90dJv/ay/YyjG3aepwhuyKZV2r7U.iOpCHNqoajpYlauU.UB2AW', 55257579, 'cliente', 0);
/*contraseña: pipo12345*/

insert into usuario values('camiloADM', '$2y$10$CZFluB7fxFo5As8lUlDX3uzYzAz/Tsi6.j5dH81.AUjVlspDOplx.', 54747832, 'adm', 0);
/*contraseña: camilo12345*/
insert into usuario values('camiloEMP', '$2y$10$CZFluB7fxFo5As8lUlDX3uzYzAz/Tsi6.j5dH81.AUjVlspDOplx.', 54747832, 'empleado', 0);
/*contraseña: camilo12345*/
insert into usuario values('camiloCLI', '$2y$10$CZFluB7fxFo5As8lUlDX3uzYzAz/Tsi6.j5dH81.AUjVlspDOplx.', 54747832, 'cliente', 0);
/*contraseña: camilo12345*/

insert into usuario values('pioADM', '$2y$10$9zHrLCn/LUVFOYkr/bHof.FJCJ/CgD3EA3GEKIR2MRu6vT1cjPos.', 54608321, 'adm', 0);
/*contraseña: pio12345*/
insert into usuario values('pioEMP', '$2y$10$9zHrLCn/LUVFOYkr/bHof.FJCJ/CgD3EA3GEKIR2MRu6vT1cjPos.', 54608321, 'empleado', 0);
/*contraseña: pio12345*/
insert into usuario values('pioCLI', '$2y$10$9zHrLCn/LUVFOYkr/bHof.FJCJ/CgD3EA3GEKIR2MRu6vT1cjPos.', 54608321, 'cliente', 0);
/*contraseña: pio12345*/

/*REGISTROS PARA CLIENTE*/
insert into cliente values('manuCLI');
insert into cliente values("daniela_user");
insert into cliente values("pipoCLI");
insert into cliente values("camiloCLI");
insert into cliente values("pioCLI");

/*REGISTROS PARA PRODUCTO*/
insert into producto values(null, 'Tabla de surf POLO', 250, 'Tablas', '*', 'surf', 'Almond', 1, 100, 0, 'Tabla de surf de calidad', 2, '50 of The Best Surfboard Designs.jfif', 10);
insert into producto values(null, 'Tabla de surf Crailtap', 350, 'Tablas', '*', 'surf', 'Crailtap', 1, 100, 10,'Tabla de surf de calidad', 1, 'Available Surfboards.jfif', 10);
insert into producto values(null, 'Traje de neopreno', 100, 'Indumentaria acuática', 'H', 'Traje', '21-17', 1, 100, 0, 'Traje de neopreno 21-17', 2, '2117-of-sweden-shorty-wetsuit-aquahybrid-3-2-wet-suit.jpg', 10);
insert into producto values(null, 'Pelota de waterpolo', 30, 'Pelotas', '*', 'Waterpolo', 'Mikasa', 1, 100, 15, 'Pelota de waterpolo', 1, 'Ballon waterpolo W6600W.jfif', 10);
insert into producto values(null, 'Tabla de surf Crailtap', 550, 'Tablas', '*', 'surf', 'Crailtap', 1, 100, 10, 'Tabla de surf de calidad', 1, 'Custom Surfboard Design Ideas _ Gift for Surfers.jfif', 10);
insert into producto values(null, 'Patas de rana genéricas', 30, 'Indumentaria acuática', '*', 'Patas de rana', 'Genérica', 1, 100, 5, 'Patas de rana genéricas', 0, 'Fins Snorkel Foot Flippers Diving Fins Beginner Water Sports - black _ China _ L_XL.jfif', 10);
insert into producto values(null, 'Lentes de agua genéricos', 20, 'Indumentaria acuática', '*', 'Lentes', 'Genérica', 1, 20, 0, 'Lentes para ver debajo del agua, ideales para niños y niñas amantes del buceo!', 0, 'xbase s swimming goggles.jfif', 10);
insert into producto values(null, 'Esnórquel genérico', 30, 'Indumentaria acuática', '*', 'Esnórquel', 'Genérica', 1, 20, 10, 'Set de esnorquel de excelente calidad. Ideal para vacacionar en el caribe... O en tu casa.', 0, 'The Explorer Set.jfif', 10);
insert into producto values(null, 'Lentes para agua', 60, 'Indumentaria acuática', '*', 'Lentes', 'Speedo', 1, 50, 0, 'Lentes de agua profesionales de calidad inigalable, antiempañado. Perfectos para nadar en piscinas olímpicas o para pasar un buen rato en la playa', 0, 'Speedo Speed Socket 2_0 Mirrored Swim Goggles, Blue.jfif', 10);
insert into producto values(null, 'Tabla de surf Crailtap', 350, 'Tablas', '*', 'surf', 'Crailtap', 1, 100, 0, 'Tabla de surf Crailtap, de excelente calidad y diseño envidiable. Perfecta para aquellos que quieren inicar en el mundo del surf.', 0, 'Surfboard Designs.jfif', 10);
insert into producto values(null, 'Tabla de surf Disrupt', 600, 'Tablas', '*', 'surf', 'Disrupt', 1, 30, 0, 'Tabla de surf Disrupt. Una de las mejores tablas de la marca. Diseñada cuidadosamente y refinada por los mejores en el oficio.', 0, 'Online Creative Portfolios and Creative Jobs - The Loop.jfif', 10);
insert into producto values(null, 'Pelota de Fútbol', 70, 'Pelotas', '*', 'Fútbol', 'Topper', 1, 10, 0, 'Pelota de fútbol Topper de calidad Fifa, perfecta para coleccionar o para jugar en canchas de pasto sintético o natural.', 0, 'pelota_futbol.PNG', 10);
insert into producto values(null, 'Pelota de Fútbol', 80, 'Pelotas', '*', 'Fútbol', 'Adidas', 1, 10, 0, 'Pelota de Fútbol Adidas, calidad excpecional. Diseño de la pelota utilizada en la final 2022 de la Uefa Champions League', 0, 'pelota_futbol_2.PNG', 10);
insert into producto values(null, 'Pelota de Basketball', 70, 'Pelotas', '*', 'Basketball', 'Topper', 1, 10, 0, 'Pelota de basketball Topper, grip de calidad para garantizar un juego excepcional.', 0, 'pelota_basket.PNG', 10);
insert into producto values(null, 'Pelota de Fútbol Nike', 70, 'Pelotas', '*', 'Fútbol', 'Nike', 1, 10, 10, 'Pelota de fútbol Nike Mercurial, ideal para fútbol sala o para practicar skills.', 0, 'pelota_futbol_3.PNG', 10);
insert into producto values(null, 'Pelota de Fútbol Nike', 70, 'Pelotas', '*', 'Fútbol', 'Nike', 1, 1, 10, 'Pelota de fútbol Nike Strike de la Premier League, ideal para fútbol de salón o para practicar skills', 0, 'pelota_futbol_4.PNG', 10);
insert into producto values(null, 'Pelota de Fútbol', 80, 'Pelotas', '*', 'Fútbol', 'Adidas', 1, 10, 0, 'Pelota de Fútbol Adidas, calidad excpecional. Diseño de la pelota utilizada en la final 2022 de la Uefa Champions League (diseño alterno).', 0, 'pelota_futbol_5.PNG', 10);
insert into producto values(null, 'Tabla de Surf Channel Island', '990', 'Tablas', '*', 'surf', 'Channel Island', 1, 10, 20, "Tabla de surf Channel Island MINI 5'11'' X 20.5' X 2.7' - 38.L. Ideal para disfrutar en cualquier playa.", 2, 'channel-island-mini.jpg', 0);
insert into producto values(null, 'Tabla de surf LOST RNF', '900', 'Tablas', '*', 'surf', 'Lost RNF', 1, 5, 0, "Tabla de surf LOST RNF 6'4'' X 22.00'' X 2.75'' - 43.5L. Modelo Light Speed, perfecta si quieres tomártelo con calma al principio, o no...", 0, 'lost-rnf.jpg', 0);
insert into producto values(null, 'Pantalon Under Armour', 27, 'pantalones', 'H', null, 'Under Armour', 1, 50, 0, 'No hay nada más cómodo que este polerón. Su tela extraelástica se mueve contigo, no contra ti, para que nunca te sientas retenido en el campo.', 0, 'pantalon-UA.jfif', 5);
insert into producto values(null, 'Pantalon Adidas', 30, 'pantalones', 'H', null, 'Adidas', 1, 43, 0, 'Este pantalón adidas luce un diseńo suave y transpirable que lo convertirá pronto en tu favorito. La pernera más estrecha a la altura del tobillo crea un look estilizado sin resultar demasiado ajustada. Presenta bolsillos frontales para guardar las llaves y el móvil.', 0, 'pantalon-ADIDAS.jfif', 10);
insert into producto values(null, 'Calza', 25, 'calzas', 'M', null, 'Genérica', 1, 23, 0, 'Calza de mujer. La mezcla de poliéster elástico te ofrece sujeción en todos tus movimientos y los paneles de malla de la parte posterior de las rodillas proporcionan ventilación y frescura kilómetro tras kilómetro.', 0, 'calza-mujer-Generica.jfif', 10);
insert into producto values(null, 'Calza Nike', 35, 'calzas', 'M', null, 'Nike', 1, 20, 0, 'Estas calzas son especialmente desenvueltas para mujeres modernas, con un modelaje diferenciado que se ajusta a tu cuerpo de manera confortable y osada.', 0, 'calza-mujer-NIKE.jfif', 10);
insert into producto values(null, 'Campera Puma', 100, 'camperas', '*', null, 'Puma', 1, 20, 0, 'Solo veo genial. Nuestra colección principal se intensifica. Esta colección es para aquellos que disfrutan de la batalla tanto como de ganar. Un ajuste rediseñado para un alto rendimiento y una portabilidad perfecta. Para tu comodidad fuera de la cancha y tus partidos dentro de la cancha.', 0, 'campera-PUMA.jpg', 10);
insert into producto values(null, 'Campera Puma', 50, 'camperas', '*', null, 'Puma', 1, 25, 0, 'Solo veo genial. Nuestra colección principal se intensifica. Esta colección es para aquellos que disfrutan de la batalla tanto como de ganar. Un ajuste rediseñado para un alto rendimiento y una portabilidad perfecta. Para tu comodidad fuera de la cancha y tus partidos dentro de la cancha.', 0, 'campera-Generica.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo', 100, 'conjuntos deportivos', 'H', null, 'Puma', 1, 30, 0, 'Tela cómoda y transpirable, de secado rápido que absorbe la humedad, adecuada para todas las estaciones.', 0, 'conjunto-deportivo-PUMA.jfif', 10);
insert into producto values(null, 'Conjunto Deportivo', 75, 'conjuntos deportivos', 'M', null, 'Puma', 1, 15, 0, 'Este conjunto puma te brinda una opción casual para tu día con su diseño.', 0, 'conjunto-deportivo-mujer-PUMA.jpg', 10);
insert into producto values(null, 'Short Nike', 20, 'shorts', 'H', null, 'Nike', 1, 20, 0, 'Este short Nike ofrece gran rendimiento y ligereza. Los materiales transpirables mantienen la frescura y la comodidad en cada kilómetro', 0, 'short-NIKE.jfif', 10);
insert into producto values(null, 'Short Adidas', 25, 'shorts', 'H', null, 'Adidas', 1, 15, 0, 'Perfecciona tus habilidades de golpeo con estos pantalones cortos de fútbol para hombre. Desde ejercicios hasta entrenamientos, ayudan a mantenerte seco y cómodo al eliminar la humedad de tu cuerpo. Cuentan con un cordón en la cintura elástica para un ajuste seguro.', 0, 'short-ADIDAS.jfif', 10);
insert into producto values(null, 'Traje de baño genérico', 25, 'trajes de baño', 'M', null, 'Genérica', 1, 10, 0, 'Material: 82% nailon y 18% elastano, la mejor tela para trajes de baño, tacto sedoso y cómodo.', 0, 'traje-baño.jpg', 10);
insert into producto values(null, 'Traje de baño genérico', 35, 'trajes de baño', 'M', null, 'Genérica', 1, 10, 0, 'Trajes de baño de una pieza totalmente forrados, panel de malla de control de barriga. Bandas de rayas estiradas alrededor de la cintura, crean una silueta adelgazante.', 0, 'traje-baño-2.jpg', 10);
insert into producto values(null, 'Pollera genérica', 13, 'polleras', 'M', null, 'Genérica', 1, 20, 0, 'Esta versátil falda skater es imprescindible para crear un conjunto increíble / Falda skater con cuerpo de punto elástico con paneles y cintura elástica oculta.', 0, 'pollera.jpg', 10);
insert into producto values(null, 'Pollera genérica', 12, 'polleras', 'M', null, 'Genérica', 1, 20, 0, 'Favorecedora cintura ancha cruzada, diseño de talle alto que ofrece soporte y comodidad, 2 bolsillos laterales en los pantalones cortos para guardar el teléfono.', 0, 'pollera-2.jpg', 10);
insert into producto values(null, 'Top Nike', 12, 'tops', 'M', null, 'Nike', 1, 20, 0, 'Top Nike para mujer que utiliza un ajuste ceñido de compresión que brinda soporte medio para una variedad de actividades de entrenamiento.', 0, 'top-2.jpg', 10);
insert into producto values(null, 'Top Nike', 12, 'tops', 'M', null, 'Nike', 1, 20, 0, 'El sujetador deportivo Nike tiene un cuerpo exterior y un forro interior hechos de material de jersey Dri-FIT. El sujetador deportivo aleja el sudor de la piel para mantenerte seca y cómoda.', 0, 'top-2.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Real Madrid 22/23', 109, 'camisetas de futbol', 'H', null, 'Adidas', 1, 25, 0, 'Cuello de polo acanalado con cierre de botón. Interlock 100% poliéster reciclado.', 0, 'futbol-Adidas-RM.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Real Madrid 22/23', 99, 'camisetas de futbol', 'H', null, 'Adidas', 1, 25, 0, 'Cuello de polo acanalado con cierre de botón. Interlock 100% poliéster reciclado.', 0, 'futbol-Adidas-RM-2.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Real Madrid 20/21', 87, 'camisetas de futbol', 'H', null, 'Adidas', 1, 25, 0, 'Regular fit, Cuello alto. 51 % poliéster reciclado, 49 % poliéster punto doble.', 0, 'futbol-Adidas-RM-3.jpg', 10);
insert into producto values(null, 'Camiseta Futbol Barcelona Entrenamiento', 40, 'camisetas de futbol', 'H', null, 'Nike', 1, 10, 0, '100% poliéster. Importado.', 0, 'futbol-Nike-Barcelona.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Barcelona 20/21', 120, 'camisetas de futbol', 'H', null, 'Nike', 1, 25, 0, '100% Poliéster, Manga corta.', 0, 'futbol-Nike-Barcelona-2.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Atlético Madrid 19/20', 50, 'camisetas de futbol', 'H', null, 'Nike', 1, 10, 0, 'Cuello retro y emblema de Nike y siete estrellas que recubren el cuello, representan la cresta atlética y el escudo de armas de Madrid.', 0, 'futbol-Nike-AM.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Atlético Madrid 20/21', 90, 'camisetas de futbol', 'H', null, 'Nike', 1, 10, 0, 'El diseño se inspira en la camiseta original de 2009/10 al traer de regreso las rayas sobre la manga.', 0, 'futbol-Nike-AM-2.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Peñarol Puma 2022', 90, 'camisetas de futbol', 'H', null, 'Puma', 1, 25, 0, 'Camiseta Puma inspirada en la conquista del segundo Quinquenio de Oro ganado por Peñarol entre 1993 y 1997, al cumplirse 25 años de la histórica gesta.', 0, 'futbol-Puma-Peñarol.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Nacional Umbro 2022', 125, 'camisetas de futbol', 'H', null, 'Umbro', 1, 30, 0, 'Detalle 120 años vistiendo de blanco. Manga corta. 100% polyester.', 0, 'futbol-Umbro-Nacional.jpg', 10);
insert into producto values(null, 'Pelota Basketball Nike Dominate 8P', 28, 'pelotas', '*', null, 'Nike', 1, 30, 0, 'Este balón de baloncesto Nike Dominate 8P en tamaño 6 está hecho de 100% goma.', 0, 'pelota-basket.jpg', 10);
insert into producto values(null, 'Pelota Basketball Nike', 19, 'pelotas', '*', null, 'Nike', 1, 20, 0, 'Diseño compacto de goma con manguera adjunta para facilitar el transporte', 0, 'pelota-basket-nike.jpg', 10);
insert into producto values(null, 'Pelota Basketball Nike', 20, 'pelotas', '*', null, 'Nike', 1, 20, 0, 'La pelota Nike Skills Basketball es tu herramienta para ser el As en la línea de 3. Su diseño con un grip suave y delicado te permite mejorar tu técnica estés donde estés.', 0, 'pelota-basket-nike-2.jpg', 10);
insert into producto values(null, 'Pelota Futbol', 28, 'pelotas', '*', null, 'Nike', 1, 30, 0, 'Pelota de futbol muy comoda para el pie, 15% poliuretano, 13% poliéster, 12% etileno acetato de vinilo.', 0, 'pelota-futbol-nike.jpg', 10);
insert into producto values(null, 'Short deportivo', 15, 'shorts', 'M', null, 'Genérica', 1, 15, 0, 'Bolsillo con cremallera en la cintura elástica para pantalones cortos exteriores tejidos esenciales.', 0, 'short-Generico-mujer.jpg', 10);
insert into producto values(null, 'Short Under Armour', 28, 'shorts', 'M', null, 'Under Armour', 1, 15, 0, 'Tejido ligero que ofrece una comodidad y durabilidad superiores. El material absorbe el sudor y se seca muy rápido.', 0, 'short-UA-mujer.jpg', 10);
insert into producto values(null, 'Short Nike', 23, 'shorts', 'M', null, 'Nike', 1, 15, 0, 'El tejido NIKE Dry te ayuda a mantenerte seco y cómodo. Entrepierna de 3.5" para cobertura y movimiento ideales.', 0, 'short-NIKE-mujer.jpg', 10);
insert into producto values(null, 'Short deportivo', 13, 'shorts', 'M', null, 'Genérica', 1, 15, 0, 'Pantalones cortos para correr, están hechos de tela 100% poliéster súper transpirable, que es más duradera y cómoda.', 0, 'short-Generico-mujer-2.jpg', 10);
insert into producto values(null, 'Short Nike', 20, 'shorts', 'H', null, 'Nike', 1, 15, 0, '80% Algodón, 20% Poliéster. Importado. Cierre con cordón.', 0, 'short-NIKE-2.jpg', 10);
insert into producto values(null, 'Short Adidas', 22, 'shorts', 'H', null, 'Adidas', 1, 15, 0, 'Pantalones cortos cómodos para hombres para hacer ejercicio. El ajuste regular logra un equilibrio cómodo entre holgado y ajustado.', 0, 'short-ADIDAS-2.jpg', 10);
insert into producto values(null, 'Short Adidas', 24, 'shorts', 'H', null, 'Adidas', 1, 15, 0, 'Pantalones cortos absorbentes de humedad para hombre para fútbol.', 0, 'short-ADIDAS-3.jpg', 10);
insert into producto values(null, 'Short Adidas', 29, 'shorts', 'H', null, 'Adidas', 1, 15, 0, 'Pantalones cortos de fútbol confeccionados con tela absorbente de sudor para una comodidad transpirable.', 0, 'short-ADIDAS-4.jpg', 10);
insert into producto values(null, 'Short Adidas', 21, 'shorts', 'H', null, 'Adidas', 1, 15, 0, 'Pantalones cortos de entrenamiento 100% poliéster importado con cierre de cordón Lavado a máquina para hombres con bolsillos con cremallera', 0, 'short-ADIDAS-5.jpg', 10);
insert into producto values(null, 'Campera Adidas', 42, 'camperas', 'M', null, 'Adidas', 1, 20, 0, 'Capera deportiva de fútbol para mujer para mayor calidez y comodidad.', 0, 'campera-ADIDAS-mujer.jpg', 10);
insert into producto values(null, 'Campera Adidas', 65, 'camperas', 'H', null, 'Adidas', 1, 20, 0, 'El ajuste regular logra un equilibrio cómodo, mangas largas con puños acanalados.', 0, 'campera-ADIDAS.jpg', 10);
insert into producto values(null, 'Campera Adidas', 43, 'camperas', 'H', null, 'Adidas', 1, 20, 0, 'Cierre de cremallera, lavado a máquina, campera adidas para hombre.', 0, 'campera-ADIDAS-2.jpg', 10);
insert into producto values(null, 'Campera Adidas', 32, 'camperas', 'H', null, 'Adidas', 1, 20, 0, 'Chaqueta cortavientos 100% poliéster importada para hombres para entrenamiento de fútbol concentrado.', 0, 'campera-ADIDAS-3.jpg', 10);
insert into producto values(null, 'Campera', 32, 'camperas', '*', null, 'Genérica', 1, 20, 0, 'Raya con logotipo de jacquard en las mangas y los hombros. Bolsillo delantero doble abierto.', 0, 'campera-Generica.jpg', 10);
insert into producto values(null, 'Campera', 31, 'camperas', '*', null, 'Genérica', 1, 20, 0, 'Cremallera está en el lado izquierdo. A prueba de viento: la chaqueta ligera con capucha cuenta con cordón ajustable y cierre de velcro.', 0, 'campera-Generica-2.jpg', 10);
insert into producto values(null, 'Campera Champion', 99, 'camperas', 'H', null, 'Champion', 1, 20, 0, 'Resistente al agua y al viento, parte inferior abierta con cordones de calcetín en la cintura.', 0, 'campera-CHAMPION-3.jpg', 10);
insert into producto values(null, 'Campera Under Armour', 43, 'camperas', 'H', null, 'Under Armour', 1, 20, 0, 'Under Armour rompevientos Sportstyle.', 0, 'campera-UA.jpg', 10);
insert into producto values(null, 'Campera Puma', 21, 'camperas', 'H', null, 'Puma', 1, 20, 0, 'Cierre de cremallera, lavado a máquina ,ajuste regular ,hecho con poliéster reciclado.', 0, 'campera-PUMA-3.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo', 30, 'conjuntos deportivos', 'H', null, 'Genérica', 1, 15, 0, 'Lavar a máquina, no usar lejía, Lavar a mano, con cordón, cierre elástico', 0, 'conjunto-deportivo.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Reebok', 27, 'conjuntos deportivos', 'H', null, 'Reebok', 1, 15, 0, 'Conjunto de chándal para niños con camiseta de manga corta, pantalones de chándal de forro polar y una sudadera con capucha a juego.', 0, 'conjunto-deportivo-REEBOK.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Rebook', 43, 'conjuntos deportivos', 'H', null, 'Reebok', 1, 15, 0, 'Conjunto de jogger para niños; la grandeza no viene de estar quieto, vivir una vida activa permite a las personas ser lo mejor de sí mismos.', 0, 'conjunto-deportivo-REEBOK-2.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Adidas', 42, 'conjuntos deportivos', 'H', null, 'Adidas', 1, 15, 0, 'Importado, cierre "pull on", lavado a máquina.', 0, 'conjunto-deportivo-ADIDAS.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Adidas', 45, 'conjuntos deportivos', 'H', null, 'Adidas', 1, 15, 0, 'Conjunto deportivo Adidas perfecto para deporte, refrescante en cualquier condición.', 0, 'conjunto-deportivo-ADIDAS-2.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Reebok', 21, 'conjuntos deportivos', 'H', null, 'Reebok', 1, 15, 0, 'Con este conjunto de ropa de juego de forro polar para bebés y niños pequeños, tu hijo recibe dos sudaderas con capucha supersuaves y un par de pantalones de chándal a juego.', 0, 'conjunto-deportivo-REEBOK-3.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Rebook', 23, 'conjuntos deportivos', 'H', null, 'Reebok', 1, 15, 0, 'Este conjunto de jogger para niños es hecho de tela supercómoda y con estampados geniales para elegir, a tu hijo le encantará este conjunto de ropa.', 0, 'conjunto-deportivo-REEBOK-4.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Rebook', 23, 'conjuntos deportivos', 'H', null, 'Reebok', 1, 15, 0, 'Con este conjunto de ropa de juego de vellón para bebés y niños pequeños, su hijo obtiene dos sudaderas con capucha súper suaves y un par de pantalones de chándal a juego.', 0, 'conjunto-deportivo-REEBOK-5.jpg', 10);
insert into producto values(null, 'Conjunto Deportivo Puma', 32, 'conjuntos deportivos', 'H', null, 'Puma', 1, 15, 0, 'Sin cierre de cierre, Lavar a máquina, Cintura elástica, Bolsillos laterales en las costuras', 0, 'conjunto-deportivo-PUMA-2.jpg', 10);
insert into producto values(null, 'Traje de neopreno', 70, 'Indumentaria acuática', 'H', 'Traje', 'NATYFLY', 1, 100, 0, '-', 2, 'traje-neopreno.jpg', 10);
insert into producto values(null, 'Traje de neopreno', 58, 'Indumentaria acuática', 'H', 'Traje', 'Zcco', 1, 100, 0, '-', 2, 'traje-neopreno-2.jpg', 10);
insert into producto values(null, 'Traje de neopreno', 87, 'Indumentaria acuática', 'H', 'Traje', 'Skyone', 1, 100, 0, '-', 2, 'traje-neopreno-3.jpg', 10);
insert into producto values(null, 'Esnórquel', 25, 'Indumentaria acuática', '*', 'Esnórquel', 'Genérica', 1, 100, 0, 'Esnórquel generico', 2, 'esnorquel.jpg', 10);
insert into producto values(null, 'Esnórquel', 20, 'Indumentaria acuática', '*', 'Esnórquel', 'Genérica', 1, 100, 0, 'Esnórquel generico', 2, 'esnorquel-2.jpg', 10);
insert into producto values(null, 'Patas de rana genéricas', 53, 'Indumentaria acuática', '*', 'Patas de rana', 'Genérica', 1, 100, 0, 'Patas de rana genéricas', 2, 'patas-rana.jpg', 10);
insert into producto values(null, 'Tabla de surf WAVESTORM', 226, 'Tablas', '*', 'surf', 'WAVESTORM', 1, 100, 0, 'La tabla de surf clásica de 8 pies es una de las tablas de aprender a surfear más vendidas.', 2, 'tabla-surf.jpg', 10);
insert into producto values(null, 'Tabla de remo de pie inflable Goplus', 250, 'Tablas', '*', 'surf', 'Goplus', 1, 100, 0, 'La tabla de paddle adopta una construcción de EVA de alta calidad y puntada caída, que permite que la tabla de surf soporte una gran cantidad de presión.', 2, 'tabla-surf-2.jpg', 10);
insert into producto values(null, 'Camiseta de Futbol Nacional', 87, 'camisetas de futbol', 'H', null, 'Umbro', 1, 25, 0, 'Camiseta de nacional', 0, 'futbol-UMBRO-nacional.jpg', 10);
insert into producto values(null, 'Camiseta de Cristiano Ronaldo', 179, 'camisetas de futbol', 'H', null, 'Adidas', 1, 25, 0, 'Ultra suave y cómodo para la comodidad durante todo el día.', 0, 'futbol-CR.jpg', 10);

/*REGISTROS PARA GUARDA*/
insert into guarda values('manuCLI', 1, 2);
insert into guarda values('manuCLI', 2, 1);
insert into guarda values('daniela_user', 2, 3);
insert into guarda values('pipoCLI', 3, 1);
insert into guarda values('camiloCLI', 4, 2);
insert into guarda values('pioCLI', 4, 1);

/*REGISTROS PARA BUSQUEDA*/
insert into busqueda values('manuCLI', 1, '2022-9-20');
insert into busqueda values('manuCLI', 2, '2022-9-20');
insert into busqueda values('daniela_user', 2, '2022-9-20');
insert into busqueda values('pipoCLI', 3, '2022-9-20');
insert into busqueda values('camiloCLI', 4, '2022-9-20');
insert into busqueda values('pioCLI', 4, '2022-9-20');

/*REGISTROS PARA EMPLEADO*/
insert into empleado values("manuEMP");
insert into empleado values("daniela_user_emp");
insert into empleado values("pipoEMP");
insert into empleado values("camiloEMP");
insert into empleado values("pioEMP");

/*REGISTROS PARA ADMINISTRADOR*/
insert into adm values("manuADM");
insert into adm values("daniela_user_adm");
insert into adm values("pipoADM");
insert into adm values("camiloADM");
insert into adm values("pioADM");

/*REGISTROS PARA COMPRA*/
insert into compra values(null, '2022-8-12', 500, 'manuCLI');
insert into compra values(null, '2022-8-10', 315, 'daniela_user');
insert into compra values(null, '2022-8-12', 100, 'pipoCLI');
insert into compra values(null, '2022-8-12', 25.5, 'camiloCLI');
insert into compra values(null, '2022-8-12', 495, 'pioCLI');
insert into compra values(null, '2022-08-30', 350, 'manuCLI');

/*REGISTROS PARA TIENE (compra_tiene_producto)*/
insert into tiene values(1, 1, 2);
insert into tiene values(2, 2, 1);
insert into tiene values(3, 3, 1);
insert into tiene values(4, 4, 1);
insert into tiene values(5, 5, 1);
insert into tiene values(6, 1, 1);
insert into tiene values(6, 3, 1);

/*REGISTROS PARA CARGA (adminsitrador_carga_producto)*/
insert into carga values('manuADM', 1, '2022-8-1');
insert into carga values('manuADM', 2, '2022-8-1');
insert into carga values('manuADM', 3, '2022-8-1');
insert into carga values('manuADM', 4, '2022-8-1');
insert into carga values('pipoADM', 5, '2022-8-1');
insert into carga values('pipoADM', 6, '2022-8-1');
insert into carga values('pipoADM', 7, '2022-8-1');
insert into carga values('pipoADM', 8, '2022-8-1');
insert into carga values('pipoADM', 9, '2022-8-1');
insert into carga values('camiloADM', 10, '2022-8-1');
insert into carga values('camiloADM', 11, '2022-8-1');
insert into carga values('camiloADM', 12, '2022-8-1');
insert into carga values('camiloADM', 13, '2022-8-1');
insert into carga values('pioADM', 14, '2022-8-1');
insert into carga values('pioADM', 15, '2022-8-1');
insert into carga values('pioADM', 16, '2022-8-1');
insert into carga values('pioADM', 17, '2022-8-1');

/*REGISTROS PARA GESTIONA (empleado_gestiona_producto)*/
insert into gestiona values(null, '2022-8-4', '20', 'Agregó stock', 'manuEMP', 1);
insert into gestiona values(null, '2022-8-4', '20', 'Quitó stock', 'daniela_user_emp', 2);
insert into gestiona values(null, '2022-8-4', null, 'Ocultó', 'pipoEMP', 3);
insert into gestiona values(null, '2022-8-4', null, 'Publicó', 'camiloEMP', 3);
insert into gestiona values(null, '2022-8-4', '20', 'Agregó stock', 'pioEMP', 4);

/*----------------------------------CREACIÓN DE VISTAS-------------------------------*/

/*VISTA PARA INFORMACIÓN DE COMPRAS*/
create view v_info_compras as
SELECT compra_tiene.*, nomb_prod, precio, categoria, comprados FROM producto JOIN
(SELECT tiene.*, monto, fecha, nomb_usu FROM tiene JOIN compra ON tiene.id_compra = compra.id_compra) compra_tiene
ON compra_tiene.id_prod = producto.id_prod;

/*VISTA PARA INFORMACIÓN DE CARRITO*/
create view v_cart as 
SELECT producto.*, cantidad, nomb_usu FROM guarda JOIN producto ON guarda.id_prod = producto.id_prod;

/*VISTA PARA INFORMACIÓN DE GESTIONES*/
create view v_gestiones as
SELECT id_gestion, fecha, info, accion, nomb_usu, producto.id_prod, nomb_prod
FROM gestiona JOIN producto ON producto.id_prod = gestiona.id_prod
ORDER BY id_gestion DESC;

/*VISTA PARA INFORMACIÓN DE PERSONA Y USUARIO*/
create view v_usr_per as
SELECT user_per.*, num FROM
(SELECT persona.*, nomb_usu, pass, tipo, suspendido FROM
persona JOIN usuario ON persona.ci=usuario.ci) user_per
JOIN tel ON user_per.ci=tel.ci;

/*VISTA PARA INFORMACIÓN DE HISTORIAL DE PRODUCTOS VISITADOS*/
create view v_historial as
SELECT producto.*, nomb_usu, fecha FROM busqueda
JOIN producto
ON busqueda.id_prod = producto.id_prod
ORDER BY fecha DESC;
/*-------------------------------------- CONSULTAS --------------------------------------*/
/*----------PEDIDAS POR DOCENTE----------*/

/*DATOS DE LA COMPRA INCLUYENDO NOMBRE DE LOS PRODUCTOS PARA UNA COMPRA DETERMINADA*/
create view v_info_compra as select info.id_compra as 'Código compra', fecha as Fecha,
monto as 'Valor total', nomb_usu as 'Comprador', nomb_prod as 'Nombre producto',
producto.precio as 'Precio producto', producto.descuento as 'Descuento producto', cantidad as 'Unidades producto'
from (select compra.id_compra, fecha, monto, nomb_usu, id_prod, cantidad 
from compra join tiene 
on tiene.id_compra = compra.id_compra and
compra.id_compra = 6
) info join producto on producto.id_prod = info.id_prod;

/*DATOS DEL PRODUCTO INCLUYENDO NOMBRE DEL USUARIO ADM Y NOMBRE DE PERSONA*/
create view v_info_prod as select nombre, apellido, cedu.nomb_usu, cedu.id_prod, fecha, nomb_prod, categoria, precio, descuento from
(select usu.nomb_usu, ci, usu.id_prod, fecha, nomb_prod, categoria, precio, descuento from
(select nomb_usu, carga.id_prod, fecha, nomb_prod, categoria, precio, descuento from
carga join producto
on carga.id_prod = producto.id_prod) usu join usuario
on usu.nomb_usu = usuario.nomb_usu) cedu join persona
on cedu.ci = persona.ci;

/*MOSTRAR TODAS LAS COMPRAS (CON PRODUCTOS) DE UN USUARIO*/
create view v_user_compras as select id_compra, nomb_usu, monto, fecha, comp_tiene.id_prod, cantidad,
nomb_prod, precio, categoria, genero, subcategoria, marca, público, stock,
descuento, descripcion, comprados, img from
(select compra.id_compra, compra.nomb_usu, monto, fecha, id_prod, cantidad from compra join tiene
on compra.id_compra = tiene.id_compra where 
compra.nomb_usu='manuCLI') comp_tiene join producto
on comp_tiene.id_prod = producto.id_prod;

/*PRODUCTO MAS VENDIDO ENTRE DOS FECHAS*/
create view v_max_ventas as select producto.id_prod as 'Código de producto', nomb_prod as 'Nombre de producto', info.su as 'Unidades vendidas' from
(select id_prod, max(suma) su from
(select id_prod, sum(cantidad) suma from
(select id_prod, cantidad from
(select id_compra from compra where fecha between '2022-08-10' and '2022-10-30') comp join tiene
on tiene.id_compra = comp.id_compra) comp_tiene
group by id_prod) suma_cantidad) info join producto
on info.id_prod = producto.id_prod;

/*USUARIO QUE MÁS ARTÍCULOS COMPRÓ ENTRE DOS FECHAS*/
create view v_user_max_compras as select info.*, nombre, apellido from
(select usuario.nomb_usu, mx, ci from
(select nomb_usu, max(cant) mx from
(select compra.*, id_prod, cantidad, sum(cantidad) cant from compra
join tiene on compra.id_compra = tiene.id_compra
where fecha between '2022-8-10' and '2022-11-30'
group by nomb_usu order by cant desc) compras) usr
join usuario on usr.nomb_usu = usuario.nomb_usu) info
join persona on info.ci = persona.ci;

/*USUARIO QUE MÁS DINERO GASTÓ ENTRE DOS FECHAS*/
create view v_user_max_dinero as select per.*, nombre, apellido from
(select ci, usr.* from
(select nomb_usu, max(prec) from
(select *, sum(monto) prec from compra
where fecha between '2022-8-10' and '2022-11-30'
group by nomb_usu order by prec desc) precios) usr
join usuario on usr.nomb_usu = usuario.nomb_usu) per
join persona on per.ci = persona.ci;


/*----------CONSULTAS ADICIONALES----------*/

/*INFORMACIÓN DE PRODUCTOS EN UN CARRITO PARTICULAR*/
create view v_info_prods_cart as
select producto.*, nomb_usu, cantidad from guarda
join producto on guarda.id_prod = producto.id_prod and guarda.nomb_usu = 'manuCLI';

/*INFORMACIÓN DE GESTIONES*/
create view v_info_gestiones as select id_gestion, fecha, info, accion, nomb_usu, producto.id_prod, nomb_prod from gestiona join
producto on gestiona.id_prod = producto.id_prod order by id_gestion desc;

/*HISTORIAL DE PRODUCTOS (VISITADOS, NO BÚSQUEDA)*/
create view v_prods_visitados as
select busqueda.id_prod as 'Código producto', fecha as 'Fecha', nomb_prod as 'Nombre producto', nomb_usu as 'Usuario'
from busqueda join producto on busqueda.id_prod = producto.id_prod and busqueda.nomb_usu = 'daniela_user' order by fecha desc;
