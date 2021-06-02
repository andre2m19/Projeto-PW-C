drop database if exists FlightTravelAir;
Create database FlightTravelAir;
use FlightTravelAir;

Create table Users (
  `id`       int(1) NOT NULL AUTO_INCREMENT,
  `nome`     varchar(80) NOT NULL,
  `morada`   varchar(120) NOT NULL,
  `email`    varchar(80) NOT NULL,
  `nif`      varchar(9) NOT NULL,
  `telefone` varchar(9) NOT NULL,
  `username` varchar(70) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role`     varchar(10),
   constraint pk_id primary key(id)
)ENGINE = InnoDB;


Create table Aeroporto (
`idAeroporto` int(1) NOT NULL AUTO_INCREMENT,
`nome`        varchar(50) NOT NULL,
`cidade`      varchar(50) NOT NULL,
`pais`        varchar(50) NOT NULL,
   constraint pk_idAeroporto primary key(idAeroporto)
) ENGINE = InnoDB;


Create table Voo (
`idVoo`      int(1) NOT NULL AUTO_INCREMENT,
`preco`      varchar(255) NOT NULL,
constraint pk_idVoo primary key(idVoo)
) ENGINE = InnoDB;


Create table Passagem_venda (
`idPassagem` int(1) DEFAULT NULL,
constraint fk_idPassagem foreign key(idPassagem) references Users (id),
constraint fk_idPassage foreign key(idPassagem) references Voo (idVoo)
) ENGINE = InnoDB;

Create table Escala (
`idEscala` int(1) NOT NULL AUTO_INCREMENT,
`data_destino` date,
`data_origem` date,
`distancia` varchar (255),
constraint fk_idEscala foreign key(idEscala) references Voo (idVoo),
constraint fk_idEscalas foreign key(idEscala) references Aeroporto (idAeroporto)
) ENGINE = InnoDB;

Create table Aviao (
`idAviao` int(1) NOT NULL AUTO_INCREMENT,
 `referencia` varchar(255) NOT NULL,
`lotacao` varchar (80) NOT NULL,
`tipo_aviao` varchar (60) NOT NULL,
constraint pk_idAviao primary key(idAviao)
) ENGINE = InnoDB;

Create table Escala_Aviao (
`idEscalaAviao` int(1) DEFAULT NULL,
constraint fk_idEscalaAviao foreign key(idEscalaAviao) references Escala (idEscala),
constraint fk_idEscalaAviao1 foreign key(idEscalaAviao) references Aviao (idAviao),
`checkin` int(60) NOT NULL,
`numero_bilhetes` int(60)
) ENGINE = InnoDB;
