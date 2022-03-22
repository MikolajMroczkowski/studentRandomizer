create table if not exists users
(
    id       int auto_increment
        primary key,
    username text not null,
    password text not null,
    constraint users_id_uindex
        unique (id)
);

create table if not exists classes
(
    id    int auto_increment
        primary key,
    name  text null,
    owner int  not null,
    constraint classes_id_uindex
        unique (id),
    constraint classes_users_id_fk
        foreign key (owner) references users (id)
            on delete cascade
);

create table if not exists students
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
        foreign key (class) references classes (id)
            on delete cascade
);

create table if not exists probability
(
    id      int auto_increment
        primary key,
    student int null,
    constraint probability_id_uindex
        unique (id),
    constraint probability_students_id_fk
        foreign key (student) references students (id)
            on delete cascade
);

