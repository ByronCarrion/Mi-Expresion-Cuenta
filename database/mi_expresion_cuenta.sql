create database if not exists expresioncuenta;
use expresioncuenta;
create table if not exists login
(
usuario varchar(25) not null,
correo varchar(50) not null,
pasword varchar(8) not null,
primary key (usuario)
);


create table if not exists nivel
(idnivel int not null,
nombrenivel varchar(25),
usuario varchar(50),
primary key (idnivel),
foreign key (usuario) references login(usuario) 
) ;
create table if not exists actividades
(idacti int not null,
nombreacti varchar(25),
idnivel int not null,
primary key (idacti),
foreign key (idnivel) references nivel(idnivel)
);
create table if not exists evaluacion
(idevaluacion int not null,
idacti int not null,
nota int,
primary key (idevaluacion),
foreign key (idacti) references actividades(idacti)
);