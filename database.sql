create table if not exists studentRandomizer.classes
(
    id   int auto_increment
    primary key,
    name text null,
    constraint classes_id_uindex
    unique (id)
    );

create table if not exists studentRandomizer.students
(
    id      int auto_increment
    primary key,
    name    text null,
    surname text null,
    class   int  null,
    number  int  null,
    constraint table_name_id_uindex
    unique (id),
    constraint students_classes_id_fk
    foreign key (class) references studentRandomizer.classes (id)
    on delete cascade
    );

create table if not exists studentRandomizer.probability
(
    id      int auto_increment
    primary key,
    student int null,
    constraint probability_id_uindex
    unique (id),
    constraint probability_students_id_fk
    foreign key (student) references studentRandomizer.students (id)
    on delete cascade
    );

