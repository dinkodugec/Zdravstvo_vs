# c:\xampp\mysql\bin\mysql -uedunova -pedunova < D:\Aplikacija\Zdrastvo_Vukovarsko_Srijemska\zdravstvo_vukovarsko_srijemska_z.sql
drop database if exists zdravstvo_vukovarsko_srijemska_z;
create database zdravstvo_vukovarsko_srijemska_z character set utf8mb4 COLLATE utf8mb4_croatian_ci;;
use zdravstvo_vukovarsko_srijemska_z;

#na produkciji promjeniti charset jer je inicijalni krivo postavljen
#alter database nikta_pp22 default character set utf8mb4;

create table operater(
    sifra int not null primary key auto_increment,
    email varchar(50) not null,
    lozinka char(60) not null,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    uloga varchar(10) not null
);

# lozinka je dinko123
insert into operater values(null,'edunova@edunova.com',
'$2y$12$Wwi52tY5v0GWQIxa9qNrVe2e.naAVIJC/ZR7OurUMHb0ZwXd9AezO ',
'Administrator','Edunova','admin');

# lozinka je dinko
insert into operater values(null,'oper@edunova.hr',
'$2y$12$iozrmfD44NMyElnHeXy/Pu3pvRkzLoijCyM6Iz1ORKAnWoB6Pcu7O ',
'Operater','Edunova','oper');


create table bolnica(
    sifra int not null primary key auto_increment,
    naziv varchar (50) not null,
    ravnatelj varchar (50) null,
    odjel varchar (50) not null,
    doktor varchar (50) not null
);

create table domzdravlja(
    sifra int not null primary key auto_increment,
    naziv varchar (50) not null,
    doktor varchar (50) not null,
    bolnica varchar not null,
    ordinacija varchar (50) null
);

create table pacijent (
    sifra int not null primary key auto_increment,
    ime varchar (50) not null,
    prezime varchar (50) not null,
    oib char (11),
    domzdravlja varchar not null,
    lijek varchar not null,
    bolestan boolean
);

create table bolest (
    sifra int not null primary key auto_increment,
    intervencija int not null,
    naziv varchar (50) null,
    pacijent varchar not null
);

create table intervencija (
    sifra int not null primary key auto_increment,
    vozilo int not null,
    vozac varchar not null,
    vrijeme datetime null
    
);

create table lijek (
    sifra int not null primary key auto_increment,
    naziv varchar (50) not null,
    bolest varchar not null,
    proizvodac varchar (50) null,
    cijena decimal (18,2) null
);



alter table domzdravlja add foreign key (bolnica) references bolnica (sifra);
alter table pacijent add foreign key (domzdravlja) references domzdravlja (sifra);
alter table pacijent add foreign key (lijek) references lijek (sifra);
alter table bolest add foreign key (intervencija) references intervencija (sifra);
alter table lijek add foreign key (bolest) references bolest (sifra);


insert into bolnica (sifra,naziv,ravnatelj,odjel,doktor) values
(null,'zupanja',null,'kirurgija','Ivan Ivic'),
(null,'vukovar',null,'pedijatrija','Andrija Anic'),
(null,'vinkovci',null,'onkologija','Ivan Horvat'),
(null,'otok',null,'fizioterapija','Mato Matic');

 insert into domzdravlja (sifra,naziv,doktor,bolnica,ordinacija) values
 (1,'domzdravlja zupanja',1,1,'pedijatrija'),
 (2,'domzdravlja otok',2,1,'fizioterapija'),
 (3,'domzdravlja stitar',3,1,'fizioterapija');
 
insert into intervencija (sifra,vozilo,vozac,vrijeme) values
(1,1,1,null);


insert into bolest (sifra,intervencija,naziv,pacijent) values
(1,1,'prehlada',1),
(2,1,'COVID infekcija',1),
(3,1,'gnojna angina',1),
(4,1,'prijelom noge',1);


insert into lijek (sifra,naziv,bolest,proizvodac) values
(null,'Andol',3,'Galenika'),
(null,'Astrazeneca',2,'Astrazeneca ltd'),
(null,'Pfizer',2,'Pfizer gmbh'),
(null,'Sputnik',2,'Sputnik gmbh'),
(null,'Sumamed',4,'Pliva'),
(null,'Panadol',6,'Bosna lijek'),
(null,'Augementin',6,'Krka Novo Mesto'),
(null,'Aspirin',6,'Bayer'),
(null,'Paracetamol',6,'Belupo'),
(null,'Efedrin',6,'Pliva');

 
insert into pacijent (sifra,ime,prezime,oib,domzdravlja,lijek,bolestan) values
(null,'Ivan','Maric',12345678912,2,2,true),
(null,'Ivana','Mamic',12345678912,1,3,true),
(null,'Ivano','Maric',12345678912,1,4,true),
(null,'Ivanka','Maricic',12345678912,1,2,true),
(null,'Josip','Markovic',12345678912,1,2,true);

# lozinka je a
insert into operater values(null,'edunova@edunova.com',
'$2y$12$u4F6WrRJiewAPUvmgG.Q.uChzm4AdCBkfVWFTcusQJsp0SlJBeFxG',
'Administrator','Edunova','admin');

# lozinka je o
insert into operater values(null,'oper@edunova.hr',
'$2y$12$kL5sRralxdraIcwdhgcYe.p0.l6Ij0YgnjfF/97uUC7UJGsjx8ES6',
'Operater','Edunova','oper');


select * from bolnica ;
