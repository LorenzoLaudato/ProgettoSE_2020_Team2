drop table if exists attivita cascade;
drop table if exists skills cascade;
drop table if exists richiesta cascade;
drop table if exists manutentore cascade;
drop table if exists possesso cascade;
drop table if exists svolgimento cascade;
drop table if exists giorni cascade;
drop table if exists fasciaoraria cascade;

create table Attivita(
	code varchar(10) primary key,
	tipo varchar(50),
	area varchar(50),
	tempoIntervento varchar(5),
	descrizione varchar(200),
	note varchar(200),
	pdfdirectory varchar(20000),
	nsettimana integer
);

create table Skills(
	Sid varchar(10) primary key,
	tipo varchar(100)
);

--Richiesta (Attivita, Skill)
create table Richiesta(
	attivita varchar(10),
	skill varchar(10),
	constraint pk_richiesta primary key (attivita, skill),
	constraint fk_richiesta_skills foreign key (skill) references Skills(sid),
	constraint fk_richiesta_attivita foreign key (attivita) references Attivita(code)
);

--Manutentore(Matricola, Nome)
create table Manutentore(
	matricola varchar(10) primary key,
	nome varchar(20)
);

--Possesso(Skill, Manutentore)
create table Possesso(
	manutentore varchar(10),
	skill varchar(10),
	constraint pk_possesso primary key (manutentore, skill),
	constraint fk_possesso_manutentore foreign key (manutentore) references Manutentore(matricola),
	constraint fk_possesso_skills foreign key (skill) references Skills(sid)
);

--Svolgimento(Manutentore,Attivita)
create table Svolgimento(
	attivita varchar(10),
	manutentore varchar(10),
	constraint pk_svolgimento primary key (attivita, manutentore),
	constraint fk_svolgimento_manutentore foreign key (manutentore) references Manutentore(matricola),
	constraint fk_svolgimento_attivita foreign key (attivita) references Attivita(code)
);

--Giorni(Giorno,Manutentore)
create table Giorni(
	manutentore varchar(10),
	giorno varchar(10),
	constraint pk_giorni primary key (giorno, manutentore),
	constraint fk_giorni_manutentore foreign key (manutentore) references Manutentore(matricola)
);

--FasciaOrario(Fascia,Giorno, Manutentore,Perc60Min)
create table FasciaOraria(
	manutentore varchar(10),
	giorno varchar(10),
	fascia varchar(10),
	constraint pk_fascia primary key (giorno, manutentore,fascia),
	constraint fk_fascia_giorni foreign key (giorno,manutentore) references Giorni(giorno,manutentore)
);

insert into Attivita values ('1','Mechanical','Fisciano - Molding','120','Replacement of robot 23 welding cables','The plant is closed from 00/00/20 to 00/00/20. On the remaining days, it is possible to intevene only after 15:00','pdf1','1');
insert into Attivita values ('2','Electric','Nusco - Carpentry','30','Completing the assembly of cabinets, mounting of component and devices in the cabinet, mechanical assemblies, wiring harnesses, and connectors.','On Monday it is possible to intervene until 6 pm, from Tuesday to Saturday until 8:30 pm','pdf2','1');
insert into Attivita values ('3','Hydraulic','Morra - Painting','250','Check oil levels in power unit tanks','The plant is open every day from 7:00 to 20:30','pdf3','1');
insert into Attivita values ('4','Electronics','Fisciano - Molding','90','Check that the watercourse mold is smooth and all fastening screws for fixing.','It is possible to intevene only after 14:30','pdf4','1');
insert into Attivita values ('5','Electronics','Salerno - Molding','85','Check the mold limit switch is abnormal, oblique pin. Slanting is abnormal.','It is possible to intevene only after 14:30','pdf5','2');
insert into Attivita values ('6','Electronics','Fisciano - Molding','20','Repairing the arc in the mold sleeve','It is possible to intevene only after 14:30','pdf6','2');


insert into skills(Sid, tipo) values ('1', 'Pav Certification');
insert into skills(Sid, tipo) values ('2', 'Electrical Maintenance');
insert into skills(Sid, tipo) values ('3', 'xyz- type robot knowledge');
insert into skills(Sid, tipo) values ('4', 'Knowledge of robot workstation 23');
insert into skills(Sid, tipo) values ('5', 'Experience in Machining, Fabricating, and Complex Assembly');

insert into richiesta(attivita, skill) values ('1', '1');
insert into richiesta(attivita, skill) values ('1', '2');
insert into richiesta(attivita, skill) values ('1', '3');
insert into richiesta(attivita, skill) values ('1', '4');
insert into richiesta(attivita, skill) values ('2', '3');
insert into richiesta(attivita, skill) values ('2', '5');
insert into richiesta(attivita, skill) values ('3', '1');
insert into richiesta(attivita, skill) values ('3', '4');
insert into richiesta(attivita, skill) values ('4', '1');
insert into richiesta(attivita, skill) values ('4', '2');
insert into richiesta(attivita, skill) values ('4', '4');
insert into richiesta(attivita, skill) values ('5', '3');
insert into richiesta(attivita, skill) values ('5', '2');
insert into richiesta(attivita, skill) values ('6', '4');
insert into richiesta(attivita, skill) values ('6', '5');

insert into manutentore(matricola, nome) values ('062271', 'Kylie Johnson');
insert into manutentore(matricola, nome) values ('062272', 'Amelia Hill');
insert into manutentore(matricola, nome) values ('062273', 'Olivia Brook');
insert into manutentore(matricola, nome) values ('062274', 'John Butler');
insert into manutentore(matricola, nome) values ('062275', 'Wilson Fisher');
insert into manutentore(matricola, nome) values ('062276', 'Harry Smith');

insert into possesso(manutentore, skill) values ('062271', '2');
insert into possesso(manutentore, skill) values ('062271', '4');
insert into possesso(manutentore, skill) values ('062272', '1');
insert into possesso(manutentore, skill) values ('062272', '2');
insert into possesso(manutentore, skill) values ('062272', '3');
insert into possesso(manutentore, skill) values ('062273', '1');
insert into possesso(manutentore, skill) values ('062274', '1');
insert into possesso(manutentore, skill) values ('062274', '2');
insert into possesso(manutentore, skill) values ('062274', '3');
insert into possesso(manutentore, skill) values ('062274', '4');
insert into possesso(manutentore, skill) values ('062275', '1');
insert into possesso(manutentore, skill) values ('062275', '2');
insert into possesso(manutentore, skill) values ('062275', '5');

select * from Attivita;
select * from Skills;
select * from Richiesta;
select * from manutentore;
select * from possesso;

