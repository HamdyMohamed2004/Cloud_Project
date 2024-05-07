create database FCDS;
use FCDS;
CREATE TABLE Student (
    ID INT PRIMARY KEY ,
    Name text(20) NOT NULL,
    AGE INT NOT NULL,
    CGPA float CHECK (CGPA BETWEEN 0 AND 4)
);
insert into Student
values(22012045,"Mohamed Manar Gamal",20,3.8);
insert into Student
values(22010113,"Samir Mohamed Samir ",20,3.9);
insert into Student
values(22010222,"Mohamed Tarek Mohamed",20,3.7);
insert into Student
values(22010129,"Abdelrahman Saied Abdelrahman",20,3.6);
insert into Student
values(22010086,"Hamdy Mohamed Abdelazim",20,3.5);
