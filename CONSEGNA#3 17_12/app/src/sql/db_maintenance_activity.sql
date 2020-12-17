drop table if exists attivita cascade;
drop table if exists skills cascade;
drop table if exists richiesta cascade;
drop table if exists manutentore cascade;
drop table if exists possesso cascade;
drop table if exists svolgimento cascade;
drop table if exists giorni cascade;
drop table if exists fasciaoraria cascade;
drop table if exists account cascade;
drop domain if exists n_settimana cascade;
drop domain if exists tipo_attivita cascade;
drop domain if exists tipo_Ruolo cascade;

create domain tipo_attivita as varchar(50) check(value='planned'or value='ewo' or value='extra');
create domain tipo_Ruolo as varchar(50) check(value='Planner'or value='Maintainer' or value='System Administrator');

create table Attivita(
	code integer not null primary key,
	tipo varchar(50),
	area varchar(50),
	tempoIntervento varchar(5),
	descrizione varchar(200),
	note varchar(200),
	pdfdirectory varchar(20000),
	nsettimana integer,
	tipoAttivita tipo_attivita,
	giornoewo integer,
	constraint chk_nsettimana check ( nsettimana>=1 and  nsettimana<=52),
	constraint chk_giornoewo check ( giornoewo>=1 and  giornoewo<=7)
);

create table Skills(
	Sid integer not null primary key,
	tipo varchar(100)
);

--Richiesta (Attivita, Skill)
create table Richiesta(
	attivita integer not null,
	skill integer not null,
	constraint pk_richiesta primary key (attivita, skill),
	constraint fk_richiesta_skills foreign key (skill) references Skills(sid),
	constraint fk_richiesta_attivita foreign key (attivita) references Attivita(code)
);

--Manutentore(Matricola, Nome)
create table Manutentore(
	matricola varchar(10) not null primary key,
	nome varchar(50), 
	email varchar(200)
);

--Possesso(Skill, Manutentore)
create table Possesso(
	manutentore varchar(10) not null,
	skill integer not null,
	constraint pk_possesso primary key (manutentore, skill),
	constraint fk_possesso_manutentore foreign key (manutentore) references Manutentore(matricola),
	constraint fk_possesso_skills foreign key (skill) references Skills(sid)
);

--Svolgimento(Manutentore,Attivita)
create table Svolgimento(
	attivita integer not null,
	manutentore varchar(10) not null,
	constraint pk_svolgimento primary key (attivita, manutentore),
	constraint fk_svolgimento_manutentore foreign key (manutentore) references Manutentore(matricola),
	constraint fk_svolgimento_attivita foreign key (attivita) references Attivita(code)
);

--Giorni(Giorno,Manutentore)
create table Giorni(
	manutentore varchar(10) not null,
	giorno integer not null,
	percGiornata integer not null,
	constraint chk_giorno check ( giorno>=1 and  giorno<=7),
	constraint chk_perc_giornata check ( percGiornata>=0 and  percGiornata<=100),
	constraint pk_giorni primary key (giorno, manutentore),
	constraint fk_giorni_manutentore foreign key (manutentore) references Manutentore(matricola)
);

--FasciaOrario(Fascia,Giorno, Manutentore,Perc60Min)
create table FasciaOraria(
	manutentore varchar(10),
	giorno integer,
	fascia varchar(10),
	Perc60Min integer not null,
	constraint chk_perc60min check (perc60min>=0 and perc60min<=60),
	constraint pk_fascia primary key (giorno, manutentore,fascia),
	constraint fk_fascia_giorni foreign key (giorno,manutentore) references Giorni(giorno,manutentore)
);

create table account (
nome varchar (50) not null,
username varchar(50) not null unique,
pass varchar (255) not null,
ruolo tipo_Ruolo, 
email varchar(200)
);

--Creazione funzione di trigger
create or replace function validita_ewo()
returns trigger as $BODY$
declare 
a varchar(50);
b integer;
begin
select tipoattivita,giornoewo into a,b from attivita where code=new.code;
if a='ewo' and b is null then raise exception $$'Una attività di tipo ewo deve 
necessariamente avere un giorno di intervento predefinito'$$;
else return new;
end if;
end $BODY$ language plpgsql;
--Creazione trigger
create trigger validita_ewo
after insert on attivita
for each row
execute procedure validita_ewo();
--TEST FUNZIONAMENTO TRIGGER
--INSERT INTO ATTIVITA VALUES(8,'Informatica','Nocera Inferiore','35',null,
							--'Guasto alla scheda madre di un pc','pdf6',1,'ewo',null);

insert into Attivita values (1,'Mechanical','Fisciano - Molding','120','Replacement of robot 23 welding cables','The plant is closed from 00/00/20 to 00/00/20. On the remaining days, it is possible to intevene only after 15:00','pdf1',1,'planned');

insert into Attivita values (4,'Electronics','Fisciano - Molding','90','Check that the watercourse mold is smooth and all fastening screws for fixing.','It is possible to intevene only after 14:30','pdf4',1,'planned');
insert into Attivita values (5,'Electronics','Salerno - Molding','85','Check the mold limit switch is abnormal, oblique pin. Slanting is abnormal.','It is possible to intevene only after 14:30','pdf5',2,'planned');
insert into Attivita values (6,'Electronics','Fisciano - Molding','20','Repairing the arc in the mold sleeve','It is possible to intevene only after 14:30','pdf6',2,'planned');
insert into Attivita values (7,'Hydraulic','Salerno - Molding','45','Robot motor oil has burned out','It is possible to intevene only after 14:30','pdf7',3,'planned');

-- EWO: 
insert into Attivita values (2,'Electric','Nusco - Carpentry','','','On Monday it is possible to intervene until 6 pm, from Tuesday to Saturday until 8:30 pm','',1,'ewo', 2);
insert into Attivita values (3,'Hydraulic','Morra - Painting','','','The plant is open every day from 7:00 to 20:30','',1, 'ewo',1);


insert into skills(Sid, tipo) values (1, 'Pav Certification');
insert into skills(Sid, tipo) values (2, 'Electrical Maintenance');
insert into skills(Sid, tipo) values (3, 'xyz- type robot knowledge');
insert into skills(Sid, tipo) values (4, 'Knowledge of robot workstation 23');
insert into skills(Sid, tipo) values (5, 'Experience in Machining, Fabricating, and Complex Assembly');

insert into richiesta(attivita, skill) values (1, 1);
insert into richiesta(attivita, skill) values (1, 2);
insert into richiesta(attivita, skill) values (1, 3);
insert into richiesta(attivita, skill) values (1, 4);
insert into richiesta(attivita, skill) values (4, 1);
insert into richiesta(attivita, skill) values (4, 2);
insert into richiesta(attivita, skill) values (4, 4);
insert into richiesta(attivita, skill) values (5, 3);
insert into richiesta(attivita, skill) values (5, 2);
insert into richiesta(attivita, skill) values (6, 4);
insert into richiesta(attivita, skill) values (6, 5);

insert into manutentore values ('062271', 'Kylie Johnson','kyliejohnson@gmail.com');
insert into manutentore values ('062272', 'Amelia Hill','ameliahill@hotmail.com');
insert into manutentore values ('062273', 'Olivia Brook','oliviabrook@outlook.it');
insert into manutentore values ('062274', 'John Butler','jbutler45@gmail.com');
insert into manutentore values ('062275', 'Wilson Fisher','wilsonf@gmail.com');
insert into manutentore values ('062276', 'Harry Smith','hs45@hotmail.it');

insert into possesso(manutentore, skill) values ('062271', 2);
insert into possesso(manutentore, skill) values ('062271', 4);
insert into possesso(manutentore, skill) values ('062272', 1);
insert into possesso(manutentore, skill) values ('062272', 2);
insert into possesso(manutentore, skill) values ('062272', 3);
insert into possesso(manutentore, skill) values ('062273', 1);
insert into possesso(manutentore, skill) values ('062274', 1);
insert into possesso(manutentore, skill) values ('062274', 2);
insert into possesso(manutentore, skill) values ('062274', 3);
insert into possesso(manutentore, skill) values ('062274', 4);
insert into possesso(manutentore, skill) values ('062275', 1);
insert into possesso(manutentore, skill) values ('062275', 2);
insert into possesso(manutentore, skill) values ('062275', 5);


select * from Attivita;
select * from Skills;
select * from Richiesta;
select * from manutentore;
select * from possesso;
--Inserimento giorni di disponibilità
insert into giorni values ('062271',1,80);
insert into giorni values ('062271',2,100);
insert into giorni values ('062271',3,20);
insert into giorni values ('062271',4,100);
insert into giorni values ('062271',5,50);
insert into giorni values ('062271',6,20);
insert into giorni values ('062271',7,100);

insert into giorni values ('062272',1,10);
insert into giorni values ('062272',2,40);
insert into giorni values ('062272',3,0);
insert into giorni values ('062272',4,0);
insert into giorni values ('062272',5,50);
insert into giorni values ('062272',6,70);
insert into giorni values ('062272',7,20);

insert into giorni values ('062273',1,0);
insert into giorni values ('062273',2,20);
insert into giorni values ('062273',3,30);
insert into giorni values ('062273',4,30);
insert into giorni values ('062273',5,50);
insert into giorni values ('062273',6,100);
insert into giorni values ('062273',7,100);

insert into giorni values ('062274',1,0);
insert into giorni values ('062274',2,10);
insert into giorni values ('062274',3,45);
insert into giorni values ('062274',4,80);
insert into giorni values ('062274',5,0);
insert into giorni values ('062274',6,0);
insert into giorni values ('062274',7,0);

insert into giorni values ('062275',1,20);
insert into giorni values ('062275',2,50);
insert into giorni values ('062275',3,0);
insert into giorni values ('062275',4,60);
insert into giorni values ('062275',5,100);
insert into giorni values ('062275',6,30);
insert into giorni values ('062275',7,0);

insert into giorni values ('062276',1,10);
insert into giorni values ('062276',2,0);
insert into giorni values ('062276',3,40);
insert into giorni values ('062276',4,70);
insert into giorni values ('062276',5,25);
insert into giorni values ('062276',6,10);
insert into giorni values ('062276',7,10);




--Fasce orarie maintainer 062271
insert into fasciaoraria values ('062271',1,'8-9',50);
insert into fasciaoraria values ('062271',1,'9-10',50);
insert into fasciaoraria values ('062271',1,'10-11',50);
insert into fasciaoraria values ('062271',1,'11-12',50);
insert into fasciaoraria values ('062271',1,'12-13',40);
insert into fasciaoraria values ('062271',1,'14-15',40);
insert into fasciaoraria values ('062271',1,'15-16',50);
insert into fasciaoraria values ('062271',1,'16-17',50);
insert into fasciaoraria values ('062271',1,'17-18',50);
insert into fasciaoraria values ('062271',1,'18-19',50);

insert into fasciaoraria values ('062271',2,'8-9',60);
insert into fasciaoraria values ('062271',2,'9-10',60);
insert into fasciaoraria values ('062271',2,'10-11',60);
insert into fasciaoraria values ('062271',2,'11-12',60);
insert into fasciaoraria values ('062271',2,'12-13',60);
insert into fasciaoraria values ('062271',2,'14-15',60);
insert into fasciaoraria values ('062271',2,'15-16',60);
insert into fasciaoraria values ('062271',2,'16-17',60);
insert into fasciaoraria values ('062271',2,'17-18',60);
insert into fasciaoraria values ('062271',2,'18-19',60);

insert into fasciaoraria values ('062271',3,'8-9',20);
insert into fasciaoraria values ('062271',3,'9-10',0);
insert into fasciaoraria values ('062271',3,'10-11',0);
insert into fasciaoraria values ('062271',3,'11-12',0);
insert into fasciaoraria values ('062271',3,'12-13',20);
insert into fasciaoraria values ('062271',3,'14-15',60);
insert into fasciaoraria values ('062271',3,'15-16',10);
insert into fasciaoraria values ('062271',3,'16-17',0);
insert into fasciaoraria values ('062271',3,'17-18',0);
insert into fasciaoraria values ('062271',3,'18-19',10);

insert into fasciaoraria values ('062271',4,'8-9',60);
insert into fasciaoraria values ('062271',4,'9-10',60);
insert into fasciaoraria values ('062271',4,'10-11',60);
insert into fasciaoraria values ('062271',4,'11-12',60);
insert into fasciaoraria values ('062271',4,'12-13',60);
insert into fasciaoraria values ('062271',4,'14-15',60);
insert into fasciaoraria values ('062271',4,'15-16',60);
insert into fasciaoraria values ('062271',4,'16-17',60);
insert into fasciaoraria values ('062271',4,'17-18',60);
insert into fasciaoraria values ('062271',4,'18-19',60);

insert into fasciaoraria values ('062271',5,'8-9',60);
insert into fasciaoraria values ('062271',5,'9-10',30);
insert into fasciaoraria values ('062271',5,'10-11',0);
insert into fasciaoraria values ('062271',5,'11-12',60);
insert into fasciaoraria values ('062271',5,'12-13',30);
insert into fasciaoraria values ('062271',5,'14-15',0);
insert into fasciaoraria values ('062271',5,'15-16',60);
insert into fasciaoraria values ('062271',5,'16-17',30);
insert into fasciaoraria values ('062271',5,'17-18',0);
insert into fasciaoraria values ('062271',5,'18-19',30);

insert into fasciaoraria values ('062271',6,'8-9',0);
insert into fasciaoraria values ('062271',6,'9-10',0);
insert into fasciaoraria values ('062271',6,'10-11',0);
insert into fasciaoraria values ('062271',6,'11-12',0);
insert into fasciaoraria values ('062271',6,'12-13',0);
insert into fasciaoraria values ('062271',6,'14-15',0);
insert into fasciaoraria values ('062271',6,'15-16',0);
insert into fasciaoraria values ('062271',6,'16-17',0);
insert into fasciaoraria values ('062271',6,'17-18',60);
insert into fasciaoraria values ('062271',6,'18-19',60);

insert into fasciaoraria values ('062271',7,'8-9',60);
insert into fasciaoraria values ('062271',7,'9-10',60);
insert into fasciaoraria values ('062271',7,'10-11',60);
insert into fasciaoraria values ('062271',7,'11-12',60);
insert into fasciaoraria values ('062271',7,'12-13',60);
insert into fasciaoraria values ('062271',7,'14-15',60);
insert into fasciaoraria values ('062271',7,'15-16',60);
insert into fasciaoraria values ('062271',7,'16-17',60);
insert into fasciaoraria values ('062271',7,'17-18',60);
insert into fasciaoraria values ('062271',7,'18-19',60);

--Fasce orarie maintainer 062272
insert into fasciaoraria values ('062272',1,'8-9',20);
insert into fasciaoraria values ('062272',1,'9-10',30);
insert into fasciaoraria values ('062272',1,'10-11',0);
insert into fasciaoraria values ('062272',1,'11-12',0);
insert into fasciaoraria values ('062272',1,'12-13',0);
insert into fasciaoraria values ('062272',1,'14-15',0);
insert into fasciaoraria values ('062272',1,'15-16',0);
insert into fasciaoraria values ('062272',1,'16-17',10);
insert into fasciaoraria values ('062272',1,'17-18',0);
insert into fasciaoraria values ('062272',1,'18-19',0);

insert into fasciaoraria values ('062272',2,'8-9',60);
insert into fasciaoraria values ('062272',2,'9-10',60);
insert into fasciaoraria values ('062272',2,'10-11',0);
insert into fasciaoraria values ('062272',2,'11-12',0);
insert into fasciaoraria values ('062272',2,'12-13',0);
insert into fasciaoraria values ('062272',2,'14-15',0);
insert into fasciaoraria values ('062272',2,'15-16',0);
insert into fasciaoraria values ('062272',2,'16-17',0);
insert into fasciaoraria values ('062272',2,'17-18',60);
insert into fasciaoraria values ('062272',2,'18-19',60);

insert into fasciaoraria values ('062272',3,'8-9',0);
insert into fasciaoraria values ('062272',3,'9-10',0);
insert into fasciaoraria values ('062272',3,'10-11',0);
insert into fasciaoraria values ('062272',3,'11-12',0);
insert into fasciaoraria values ('062272',3,'12-13',0);
insert into fasciaoraria values ('062272',3,'14-15',0);
insert into fasciaoraria values ('062272',3,'15-16',0);
insert into fasciaoraria values ('062272',3,'16-17',0);
insert into fasciaoraria values ('062272',3,'17-18',0);
insert into fasciaoraria values ('062272',3,'18-19',0);

insert into fasciaoraria values ('062272',4,'8-9',0);
insert into fasciaoraria values ('062272',4,'9-10',0);
insert into fasciaoraria values ('062272',4,'10-11',0);
insert into fasciaoraria values ('062272',4,'11-12',0);
insert into fasciaoraria values ('062272',4,'12-13',0);
insert into fasciaoraria values ('062272',4,'14-15',0);
insert into fasciaoraria values ('062272',4,'15-16',0);
insert into fasciaoraria values ('062272',4,'16-17',0);
insert into fasciaoraria values ('062272',4,'17-18',0);
insert into fasciaoraria values ('062272',4,'18-19',0);

insert into fasciaoraria values ('062272',5,'8-9',30);
insert into fasciaoraria values ('062272',5,'9-10',30);
insert into fasciaoraria values ('062272',5,'10-11',30);
insert into fasciaoraria values ('062272',5,'11-12',30);
insert into fasciaoraria values ('062272',5,'12-13',30);
insert into fasciaoraria values ('062272',5,'14-15',30);
insert into fasciaoraria values ('062272',5,'15-16',30);
insert into fasciaoraria values ('062272',5,'16-17',30);
insert into fasciaoraria values ('062272',5,'17-18',30);
insert into fasciaoraria values ('062272',5,'18-19',30);

insert into fasciaoraria values ('062272',6,'8-9',20);
insert into fasciaoraria values ('062272',6,'9-10',20);
insert into fasciaoraria values ('062272',6,'10-11',20);
insert into fasciaoraria values ('062272',6,'11-12',30);
insert into fasciaoraria values ('062272',6,'12-13',40);
insert into fasciaoraria values ('062272',6,'14-15',50);
insert into fasciaoraria values ('062272',6,'15-16',60);
insert into fasciaoraria values ('062272',6,'16-17',60);
insert into fasciaoraria values ('062272',6,'17-18',60);
insert into fasciaoraria values ('062272',6,'18-19',60);

insert into fasciaoraria values ('062272',7,'8-9',60);
insert into fasciaoraria values ('062272',7,'9-10',0);
insert into fasciaoraria values ('062272',7,'10-11',0);
insert into fasciaoraria values ('062272',7,'11-12',0);
insert into fasciaoraria values ('062272',7,'12-13',0);
insert into fasciaoraria values ('062272',7,'14-15',0);
insert into fasciaoraria values ('062272',7,'15-16',0);
insert into fasciaoraria values ('062272',7,'16-17',20);
insert into fasciaoraria values ('062272',7,'17-18',20);
insert into fasciaoraria values ('062272',7,'18-19',20);

--Fasce orarie maintainer 062273
insert into fasciaoraria values ('062273',1,'8-9',0);
insert into fasciaoraria values ('062273',1,'9-10',0);
insert into fasciaoraria values ('062273',1,'10-11',0);
insert into fasciaoraria values ('062273',1,'11-12',0);
insert into fasciaoraria values ('062273',1,'12-13',0);
insert into fasciaoraria values ('062273',1,'14-15',0);
insert into fasciaoraria values ('062273',1,'15-16',0);
insert into fasciaoraria values ('062273',1,'16-17',0);
insert into fasciaoraria values ('062273',1,'17-18',0);
insert into fasciaoraria values ('062273',1,'18-19',0);

insert into fasciaoraria values ('062273',2,'8-9',10);
insert into fasciaoraria values ('062273',2,'9-10',10);
insert into fasciaoraria values ('062273',2,'10-11',10);
insert into fasciaoraria values ('062273',2,'11-12',10);
insert into fasciaoraria values ('062273',2,'12-13',10);
insert into fasciaoraria values ('062273',2,'14-15',10);
insert into fasciaoraria values ('062273',2,'15-16',10);
insert into fasciaoraria values ('062273',2,'16-17',10);
insert into fasciaoraria values ('062273',2,'17-18',20);
insert into fasciaoraria values ('062273',2,'18-19',20);

insert into fasciaoraria values ('062273',3,'8-9',20);
insert into fasciaoraria values ('062273',3,'9-10',20);
insert into fasciaoraria values ('062273',3,'10-11',20);
insert into fasciaoraria values ('062273',3,'11-12',20);
insert into fasciaoraria values ('062273',3,'12-13',20);
insert into fasciaoraria values ('062273',3,'14-15',20);
insert into fasciaoraria values ('062273',3,'15-16',20);
insert into fasciaoraria values ('062273',3,'16-17',20);
insert into fasciaoraria values ('062273',3,'17-18',20);
insert into fasciaoraria values ('062273',3,'18-19',0);

insert into fasciaoraria values ('062273',4,'8-9',20);
insert into fasciaoraria values ('062273',4,'9-10',20);
insert into fasciaoraria values ('062273',4,'10-11',20);
insert into fasciaoraria values ('062273',4,'11-12',20);
insert into fasciaoraria values ('062273',4,'12-13',20);
insert into fasciaoraria values ('062273',4,'14-15',0);
insert into fasciaoraria values ('062273',4,'15-16',20);
insert into fasciaoraria values ('062273',4,'16-17',20);
insert into fasciaoraria values ('062273',4,'17-18',20);
insert into fasciaoraria values ('062273',4,'18-19',20);

insert into fasciaoraria values ('062273',5,'8-9',30);
insert into fasciaoraria values ('062273',5,'9-10',30);
insert into fasciaoraria values ('062273',5,'10-11',30);
insert into fasciaoraria values ('062273',5,'11-12',30);
insert into fasciaoraria values ('062273',5,'12-13',30);
insert into fasciaoraria values ('062273',5,'14-15',30);
insert into fasciaoraria values ('062273',5,'15-16',30);
insert into fasciaoraria values ('062273',5,'16-17',30);
insert into fasciaoraria values ('062273',5,'17-18',30);
insert into fasciaoraria values ('062273',5,'18-19',30);

insert into fasciaoraria values ('062273',6,'8-9',60);
insert into fasciaoraria values ('062273',6,'9-10',60);
insert into fasciaoraria values ('062273',6,'10-11',60);
insert into fasciaoraria values ('062273',6,'11-12',60);
insert into fasciaoraria values ('062273',6,'12-13',60);
insert into fasciaoraria values ('062273',6,'14-15',60);
insert into fasciaoraria values ('062273',6,'15-16',60);
insert into fasciaoraria values ('062273',6,'16-17',60);
insert into fasciaoraria values ('062273',6,'17-18',60);
insert into fasciaoraria values ('062273',6,'18-19',60);

insert into fasciaoraria values ('062273',7,'8-9',60);
insert into fasciaoraria values ('062273',7,'9-10',60);
insert into fasciaoraria values ('062273',7,'10-11',60);
insert into fasciaoraria values ('062273',7,'11-12',60);
insert into fasciaoraria values ('062273',7,'12-13',60);
insert into fasciaoraria values ('062273',7,'14-15',60);
insert into fasciaoraria values ('062273',7,'15-16',60);
insert into fasciaoraria values ('062273',7,'16-17',60);
insert into fasciaoraria values ('062273',7,'17-18',60);
insert into fasciaoraria values ('062273',7,'18-19',60);




--Fasce orarie maintainer 062274
insert into fasciaoraria values ('062274',1,'8-9',0);
insert into fasciaoraria values ('062274',1,'9-10',0);
insert into fasciaoraria values ('062274',1,'10-11',0);
insert into fasciaoraria values ('062274',1,'11-12',0);
insert into fasciaoraria values ('062274',1,'12-13',0);
insert into fasciaoraria values ('062274',1,'14-15',0);
insert into fasciaoraria values ('062274',1,'15-16',0);
insert into fasciaoraria values ('062274',1,'16-17',0);
insert into fasciaoraria values ('062274',1,'17-18',0);
insert into fasciaoraria values ('062274',1,'18-19',0);

insert into fasciaoraria values ('062274',2,'8-9',0);
insert into fasciaoraria values ('062274',2,'9-10',0);
insert into fasciaoraria values ('062274',2,'10-11',0);
insert into fasciaoraria values ('062274',2,'11-12',0);
insert into fasciaoraria values ('062274',2,'12-13',0);
insert into fasciaoraria values ('062274',2,'14-15',0);
insert into fasciaoraria values ('062274',2,'15-16',10);
insert into fasciaoraria values ('062274',2,'16-17',10);
insert into fasciaoraria values ('062274',2,'17-18',20);
insert into fasciaoraria values ('062274',2,'18-19',20);

insert into fasciaoraria values ('062274',3,'8-9',60);
insert into fasciaoraria values ('062274',3,'9-10',60);
insert into fasciaoraria values ('062274',3,'10-11',60);
insert into fasciaoraria values ('062274',3,'11-12',0);
insert into fasciaoraria values ('062274',3,'12-13',0);
insert into fasciaoraria values ('062274',3,'14-15',0);
insert into fasciaoraria values ('062274',3,'15-16',30);
insert into fasciaoraria values ('062274',3,'16-17',30);
insert into fasciaoraria values ('062274',3,'17-18',30);
insert into fasciaoraria values ('062274',3,'18-19',0);

insert into fasciaoraria values ('062274',4,'8-9',45);
insert into fasciaoraria values ('062274',4,'9-10',60);
insert into fasciaoraria values ('062274',4,'10-11',60);
insert into fasciaoraria values ('062274',4,'11-12',60);
insert into fasciaoraria values ('062274',4,'12-13',45);
insert into fasciaoraria values ('062274',4,'14-15',60);
insert into fasciaoraria values ('062274',4,'15-16',45);
insert into fasciaoraria values ('062274',4,'16-17',45);
insert into fasciaoraria values ('062274',4,'17-18',50);
insert into fasciaoraria values ('062274',4,'18-19',10);

insert into fasciaoraria values ('062274',5,'8-9',0);
insert into fasciaoraria values ('062274',5,'9-10',0);
insert into fasciaoraria values ('062274',5,'10-11',0);
insert into fasciaoraria values ('062274',5,'11-12',0);
insert into fasciaoraria values ('062274',5,'12-13',0);
insert into fasciaoraria values ('062274',5,'14-15',0);
insert into fasciaoraria values ('062274',5,'15-16',0);
insert into fasciaoraria values ('062274',5,'16-17',0);
insert into fasciaoraria values ('062274',5,'17-18',0);
insert into fasciaoraria values ('062274',5,'18-19',0);

insert into fasciaoraria values ('062274',6,'8-9',0);
insert into fasciaoraria values ('062274',6,'9-10',0);
insert into fasciaoraria values ('062274',6,'10-11',0);
insert into fasciaoraria values ('062274',6,'11-12',0);
insert into fasciaoraria values ('062274',6,'12-13',0);
insert into fasciaoraria values ('062274',6,'14-15',0);
insert into fasciaoraria values ('062274',6,'15-16',0);
insert into fasciaoraria values ('062274',6,'16-17',0);
insert into fasciaoraria values ('062274',6,'17-18',0);
insert into fasciaoraria values ('062274',6,'18-19',0);

insert into fasciaoraria values ('062274',7,'8-9',0);
insert into fasciaoraria values ('062274',7,'9-10',0);
insert into fasciaoraria values ('062274',7,'10-11',0);
insert into fasciaoraria values ('062274',7,'11-12',0);
insert into fasciaoraria values ('062274',7,'12-13',0);
insert into fasciaoraria values ('062274',7,'14-15',0);
insert into fasciaoraria values ('062274',7,'15-16',0);
insert into fasciaoraria values ('062274',7,'16-17',0);
insert into fasciaoraria values ('062274',7,'17-18',0);
insert into fasciaoraria values ('062274',7,'18-19',0);


--Fasce orarie maintainer 062275
insert into fasciaoraria values ('062275',1,'8-9',48);
insert into fasciaoraria values ('062275',1,'9-10',0);
insert into fasciaoraria values ('062275',1,'10-11',0);
insert into fasciaoraria values ('062275',1,'11-12',18);
insert into fasciaoraria values ('062275',1,'12-13',6);
insert into fasciaoraria values ('062275',1,'14-15',30);
insert into fasciaoraria values ('062275',1,'15-16',12);
insert into fasciaoraria values ('062275',1,'16-17',3);
insert into fasciaoraria values ('062275',1,'17-18',3);
insert into fasciaoraria values ('062275',1,'18-19',0);

insert into fasciaoraria values ('062275',2,'8-9',60);
insert into fasciaoraria values ('062275',2,'9-10',60);
insert into fasciaoraria values ('062275',2,'10-11',5);
insert into fasciaoraria values ('062275',2,'11-12',11);
insert into fasciaoraria values ('062275',2,'12-13',60);
insert into fasciaoraria values ('062275',2,'14-15',60);
insert into fasciaoraria values ('062275',2,'15-16',11);
insert into fasciaoraria values ('062275',2,'16-17',11);
insert into fasciaoraria values ('062275',2,'17-18',11);
insert into fasciaoraria values ('062275',2,'18-19',11);

insert into fasciaoraria values ('062275',3,'8-9',0);
insert into fasciaoraria values ('062275',3,'9-10',0);
insert into fasciaoraria values ('062275',3,'10-11',0);
insert into fasciaoraria values ('062275',3,'11-12',0);
insert into fasciaoraria values ('062275',3,'12-13',0);
insert into fasciaoraria values ('062275',3,'14-15',0);
insert into fasciaoraria values ('062275',3,'15-16',0);
insert into fasciaoraria values ('062275',3,'16-17',0);
insert into fasciaoraria values ('062275',3,'17-18',0);
insert into fasciaoraria values ('062275',3,'18-19',0);

insert into fasciaoraria values ('062275',4,'8-9',50);
insert into fasciaoraria values ('062275',4,'9-10',50);
insert into fasciaoraria values ('062275',4,'10-11',50);
insert into fasciaoraria values ('062275',4,'11-12',50);
insert into fasciaoraria values ('062275',4,'12-13',15);
insert into fasciaoraria values ('062275',4,'14-15',12);
insert into fasciaoraria values ('062275',4,'15-16',15);
insert into fasciaoraria values ('062275',4,'16-17',18);
insert into fasciaoraria values ('062275',4,'17-18',60);
insert into fasciaoraria values ('062275',4,'18-19',40);

insert into fasciaoraria values ('062275',5,'8-9',60);
insert into fasciaoraria values ('062275',5,'9-10',60);
insert into fasciaoraria values ('062275',5,'10-11',60);
insert into fasciaoraria values ('062275',5,'11-12',60);
insert into fasciaoraria values ('062275',5,'12-13',60);
insert into fasciaoraria values ('062275',5,'14-15',60);
insert into fasciaoraria values ('062275',5,'15-16',60);
insert into fasciaoraria values ('062275',5,'16-17',60);
insert into fasciaoraria values ('062275',5,'17-18',60);
insert into fasciaoraria values ('062275',5,'18-19',60);

insert into fasciaoraria values ('062275',6,'8-9',20);
insert into fasciaoraria values ('062275',6,'9-10',20);
insert into fasciaoraria values ('062275',6,'10-11',40);
insert into fasciaoraria values ('062275',6,'11-12',20);
insert into fasciaoraria values ('062275',6,'12-13',0);
insert into fasciaoraria values ('062275',6,'14-15',0);
insert into fasciaoraria values ('062275',6,'15-16',20);
insert into fasciaoraria values ('062275',6,'16-17',50);
insert into fasciaoraria values ('062275',6,'17-18',10);
insert into fasciaoraria values ('062275',6,'18-19',0);

insert into fasciaoraria values ('062275',7,'8-9',0);
insert into fasciaoraria values ('062275',7,'9-10',0);
insert into fasciaoraria values ('062275',7,'10-11',0);
insert into fasciaoraria values ('062275',7,'11-12',0);
insert into fasciaoraria values ('062275',7,'12-13',0);
insert into fasciaoraria values ('062275',7,'14-15',0);
insert into fasciaoraria values ('062275',7,'15-16',0);
insert into fasciaoraria values ('062275',7,'16-17',0);
insert into fasciaoraria values ('062275',7,'17-18',0);
insert into fasciaoraria values ('062275',7,'18-19',0);


--Fasce orarie maintainer 062276
insert into fasciaoraria values ('062276',1,'8-9',20);
insert into fasciaoraria values ('062276',1,'9-10',10);
insert into fasciaoraria values ('062276',1,'10-11',10);
insert into fasciaoraria values ('062276',1,'11-12',0);
insert into fasciaoraria values ('062276',1,'12-13',0);
insert into fasciaoraria values ('062276',1,'14-15',0);
insert into fasciaoraria values ('062276',1,'15-16',0);
insert into fasciaoraria values ('062276',1,'16-17',0);
insert into fasciaoraria values ('062276',1,'17-18',20);
insert into fasciaoraria values ('062276',1,'18-19',0);

insert into fasciaoraria values ('062276',2,'8-9',0);
insert into fasciaoraria values ('062276',2,'9-10',0);
insert into fasciaoraria values ('062276',2,'10-11',0);
insert into fasciaoraria values ('062276',2,'11-12',0);
insert into fasciaoraria values ('062276',2,'12-13',0);
insert into fasciaoraria values ('062276',2,'14-15',0);
insert into fasciaoraria values ('062276',2,'15-16',0);
insert into fasciaoraria values ('062276',2,'16-17',0);
insert into fasciaoraria values ('062276',2,'17-18',0);
insert into fasciaoraria values ('062276',2,'18-19',0);

insert into fasciaoraria values ('062276',3,'8-9',20);
insert into fasciaoraria values ('062276',3,'9-10',45);
insert into fasciaoraria values ('062276',3,'10-11',15);
insert into fasciaoraria values ('062276',3,'11-12',0);
insert into fasciaoraria values ('062276',3,'12-13',20);
insert into fasciaoraria values ('062276',3,'14-15',60);
insert into fasciaoraria values ('062276',3,'15-16',30);
insert into fasciaoraria values ('062276',3,'16-17',30);
insert into fasciaoraria values ('062276',3,'17-18',10);
insert into fasciaoraria values ('062276',3,'18-19',10);

insert into fasciaoraria values ('062276',4,'8-9',0);
insert into fasciaoraria values ('062276',4,'9-10',0);
insert into fasciaoraria values ('062276',4,'10-11',0);
insert into fasciaoraria values ('062276',4,'11-12',60);
insert into fasciaoraria values ('062276',4,'12-13',60);
insert into fasciaoraria values ('062276',4,'14-15',60);
insert into fasciaoraria values ('062276',4,'15-16',60);
insert into fasciaoraria values ('062276',4,'16-17',60);
insert into fasciaoraria values ('062276',4,'17-18',60);
insert into fasciaoraria values ('062276',4,'18-19',60);

insert into fasciaoraria values ('062276',5,'8-9',60);
insert into fasciaoraria values ('062276',5,'9-10',60);
insert into fasciaoraria values ('062276',5,'10-11',30);
insert into fasciaoraria values ('062276',5,'11-12',0);
insert into fasciaoraria values ('062276',5,'12-13',0);
insert into fasciaoraria values ('062276',5,'14-15',0);
insert into fasciaoraria values ('062276',5,'15-16',0);
insert into fasciaoraria values ('062276',5,'16-17',0);
insert into fasciaoraria values ('062276',5,'17-18',0);
insert into fasciaoraria values ('062276',5,'18-19',0);

insert into fasciaoraria values ('062276',6,'8-9',0);
insert into fasciaoraria values ('062276',6,'9-10',0);
insert into fasciaoraria values ('062276',6,'10-11',60);
insert into fasciaoraria values ('062276',6,'11-12',0);
insert into fasciaoraria values ('062276',6,'12-13',0);
insert into fasciaoraria values ('062276',6,'14-15',0);
insert into fasciaoraria values ('062276',6,'15-16',0);
insert into fasciaoraria values ('062276',6,'16-17',0);
insert into fasciaoraria values ('062276',6,'17-18',0);
insert into fasciaoraria values ('062276',6,'18-19',0);

insert into fasciaoraria values ('062276',7,'8-9',20);
insert into fasciaoraria values ('062276',7,'9-10',20);
insert into fasciaoraria values ('062276',7,'10-11',20);
insert into fasciaoraria values ('062276',7,'11-12',0);
insert into fasciaoraria values ('062276',7,'12-13',0);
insert into fasciaoraria values ('062276',7,'14-15',0);
insert into fasciaoraria values ('062276',7,'15-16',0);
insert into fasciaoraria values ('062276',7,'16-17',0);
insert into fasciaoraria values ('062276',7,'17-18',0);
insert into fasciaoraria values ('062276',7,'18-19',0);

