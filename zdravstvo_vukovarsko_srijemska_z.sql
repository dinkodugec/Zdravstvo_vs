drop database if exists zdravstvo_vukovarsko_srijemska_z;
create database zdravstvo_vukovarsko_srijemska_z;
use zdravstvo_vukovarsko-srijemska_z;

create table operater(
    sifra int not null primary key auto_increment,
    email varchar(50) not null,
    lozinka char(60) not null,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    uloga varchar(10) not null
);

# lozinka je a
insert into operater values(null,'edunova@edunova.com',
'$2y$12$zAAPvVr86xzy5FP3JrbRU.PuLJXPzZDAl6bxTyajT6LBl61VK/tES ',
'Administrator','Edunova','admin');

# lozinka je o
insert into operater values(null,'oper@edunova.hr',
'$2y$10$yECpl/AKVYMutwEcMTJOZeUWwJ8kk7EtafwXdhfjYqs3UX2pEUTFu',
'Operater','Edunova','oper');

create table bolnica(
    sifra int not null primary key auto_increment,
    ravnatelj varchar (50) null,
    odjel varchar (50) not null,
    doktor varchar (50) not null
);

create table dom_zdravlja(
    sifra int not null primary key auto_increment,
    doktor varchar (50) not null,
    bolnica int not null,
    ordinacija varchar (50) null
);

create table pacijent (
    sifra int not null primary key auto_increment,
    ime varchar (50) not null,
    prezime varchar (50) not null,
    oib char (11),
    dom_zdravlja int not null,
    lijek int not null,
    bolestan boolean
);

create table bolest (
    sifra int not null primary key auto_increment,
    intervencija int not null,
    naziv varchar (50) null,
    pacijent int not null
);

create table intervencija (
    sifra int not null primary key auto_increment,
    vozilo int not null,
    vozac int not null,
    vrijeme datetime null
    
);

create table lijek (
    sifra int not null primary key auto_increment,
    naziv varchar (50) not null,
    bolest int not null,
    proizvodac varchar (50) null,
    cijena decimal (18,2) null
)

alter table dom_zdravlja add foreign key (bolnica) references bolnica (sifra);
alter table pacijent add foreign key (dom_zdravlja) references dom_zdravlja (sifra);
alter table pacijent add foreign key (lijek) references lijek (sifra);
alter table bolest add foreign key (intervencija) references intervencija (sifra);
alter table lijek add foreign key (bolest) references bolest (sifra);
