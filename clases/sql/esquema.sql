create database bdphp default character set utf8 collate utf8_unicode_ci;
grant all on bdphp.* to userphp@localhost identified by 'clavephp';
flush privileges;

use bdphp;

CREATE TABLE IF NOT EXISTS rest_plato (
    id int(11) NOT NULL primary key auto_increment,
    nombre varchar(20) NOT NULL UNIQUE,
    descripcion varchar(200) NOT NULL,
    precio decimal(5,2) NOT NULL,
    foto varchar(12) NULL DEFAULT "0"
) ENGINE=InnoDB collate utf8_unicode_ci;   

insert into rest_plato values(null, 'plato 1', 'descripcion del plato 1', 999.99, "foto01.jpg");
insert into rest_plato values(null, 'plato 2', 'descripcion del plato 2', 199.99, "foto01.jpg");
insert into rest_plato values(null, 'plato 3', 'descripcion del plato 3', 0.99, "foto01.jpg");

CREATE TABLE IF NOT EXISTS rest_user (
    nombre varchar(30) NOT NULL,
    clave varchar(40) NOT NULL 
) ENGINE=InnoDB collate utf8_unicode_ci;

insert into rest_user values('root', sha1('toor'));