DROP TABLE PasswordSetting;
DROP TABLE Publish;
DROP TABLE BlogAccount;
DROP TABLE Customer;
DROP TABLE BlogSiteManager;
DROP TABLE Dependent_Fee;
DROP TABLE CustomerService;
DROP TABLE Tagging;
DROP TABLE ImageOnlyPost;
DROP TABLE LikeRelate;
DROP TABLE RepostRelate;
DROP TABLE CommentRelate;
DROP TABLE Posts;

create table CustomerService
(
    customerType CHAR(20),
    location     CHAR(20),
    serviceFee   REAL,
    primary key (customerType, location)
);

create table Dependent_Fee
(
    customerID   INTEGER,
    dependentID  INTEGER,
    customerType CHAR(20),
    location     CHAR(20),
    primary key (customerID, dependentID),
    foreign key (customerType, location) references CustomerService
        ON DELETE CASCADE
);

create table BlogSiteManager
(
    managerName CHAR(20),
    primary key (managerName)
);

create table Customer
(
    customerID  INTEGER,
    managerName CHAR(20),
    name        CHAR(20),
    primary key (customerID),
    foreign key (managerName) references BlogSiteManager
        ON DELETE CASCADE
);

create table BlogAccount
(
    userName    CHAR(20),
--     managerName CHAR(20) NOT NULL,
    customerID INTEGER,
    primary key (userName),
    foreign key (customerID) references Customer
        ON DELETE CASCADE
--     foreign key (managerName) references BlogSiteManager
);

-- create table Own
-- (
--     userName   CHAR(20) NOT NULL,
--     customerID INTEGER,
--     primary key (customerID, userName),
--     foreign key (userName) references BlogAccount,
--
-- );

create table PasswordSetting
(
    userName            CHAR(20),
    password            CHAR(30),
    passwordSettingTime CHAR(20),
    primary key (passwordSettingTime),
    foreign key (userName) references BlogAccount
        ON DELETE CASCADE
);

create table ImageOnlyPost (
                               postID INTEGER,
                               primary key (postID)
);

create table Tagging
(
    postID  INTEGER NOT NULL,
    tagName CHAR(40),
    tagID   INTEGER,
    primary key (tagID),
    foreign key (postID) references ImageOnlyPost ON DELETE SET NULL
);

create table Posts
(
    postID INTEGER,
    primary key (postID)
);

create table LikeRelate
(
    quantityOfLike Integer,
    time           CHAR(20),
    postID         Integer,
    primary key (time, postID),
    foreign key (postID) references Posts
        ON DELETE CASCADE
);

create table RepostRelate
(
    quantityOfRepost Integer,
    time             CHAR(20),
    postID           Integer,
    primary key (time, postID),
    foreign key (postID) references Posts
        ON DELETE CASCADE
);

create table CommentRelate
(
    quantityOfComment Integer,
    time             CHAR(20),
    postID           Integer,
    primary key (time, postID),
    foreign key (postID) references Posts
        ON DELETE CASCADE
);

create table Publish
(
    postID   INTEGER,
    userName CHAR(20),
    primary key (userName, postID),
    foreign key (postID) references Posts ON DELETE CASCADE,
    foreign key (userName) references BlogAccount
        ON DELETE CASCADE
);

insert into CustomerService
values ('Premier', 'China', '170');

insert into CustomerService
values ('Visitor', 'China', '125');

insert into CustomerService
values ('Normal', 'China', '150');

insert into CustomerService
values ('Normal', 'USA', '170');

insert into CustomerService
values ('Premier', 'Canada', '185');

insert into CustomerService
values ('Normal', 'Canada', '165');

insert into CustomerService
values ('Visitor', 'Canada', '130');

insert into CustomerService
values ('Premier', 'USA', '225');

insert into CustomerService
values ('Visitor', 'USA', '135');

insert into Dependent_Fee
values ('212', '200', 'Premier','China');

insert into Dependent_Fee
values ('216', '986', 'Visitor','China');


insert into Dependent_Fee
values ('222', '587', 'Normal','USA');


insert into Dependent_Fee
values ('222', '555', 'Premier','Canada');


insert into Dependent_Fee
values ('216', '202', 'Premier','USA');

insert into BlogSiteManager
values ('Thor');

insert into BlogSiteManager
values ('Natalia' );

insert into BlogSiteManager
values ('Clinton');

insert into BlogSiteManager
values ('Steven');

insert into BlogSiteManager
values ('Bruce');

insert into Customer
values ('216', 'Thor', 'ShowMaker');

insert into Customer
values ('212', 'Natalia', 'Faker');

insert into Customer
values ('222', 'Clinton', 'God');

insert into Customer
values ('241', 'Steven', 'Neko');

insert into BlogAccount
values ('Jacob','222');

insert into BlogAccount
values ('Danny', '216' );

insert into BlogAccount
values ('BinMing','212');

insert into BlogAccount
values ('James', '222');

insert into BlogAccount
values ('Wanda','241');

-- insert into Own
-- values ('Jacob', '216');
--
-- insert into Own
-- values ('Danny', '212');
--
--
-- insert into Own
-- values ('BinMing', '212');
--
--
-- insert into Own
-- values ('James', '222');
--
--
-- insert into Own
-- values ('Wanda', '241');

insert into PasswordSetting
values ('Jacob', '23490DJSAK','201203010030');


insert into PasswordSetting
values ('Danny', 'Steven','201203112230');


insert into PasswordSetting
values ('BinMing', 'Clinton','202104232322');


insert into PasswordSetting
values ('James', 'Pietro','202105221400');


insert into PasswordSetting
values ('Wanda', 'Vision','201904111100');

insert into ImageOnlyPost
values ('14562');


insert into ImageOnlyPost
values ('46978' );


insert into ImageOnlyPost
values ('11111');


insert into ImageOnlyPost
values ('95159');

insert into ImageOnlyPost
values ('75357');


insert into Tagging
values ('14562', 'Steven', '23456');

insert into Tagging
values ('95159', 'Tony', '12345');


insert into Tagging
values ('75357', 'Bruce', '11111');


insert into Tagging
values ('11111', 'Clinton', '22222');


insert into Tagging
values ('46978', 'Thor', '11223');

insert into Posts
values ('6144');

insert into Posts
values ('5846');

insert into Posts
values ('9875');

insert into Posts
values ('6841');

insert into Posts
values ('6985');

insert into LikeRelate
values ('100', '2021.2.11','6144');


insert into LikeRelate
values ('11', '2021.2.11','5846');


insert into LikeRelate
values ('45', '2021.2.11','9875');


insert into LikeRelate
values ('75', '2021.2.18','6841');


insert into LikeRelate
values ('68', '2021.2.19','6985');



insert into CommentRelate
values ('100', '2021.2.11','9875');


insert into CommentRelate
values ('11', '2021.2.11','6985');


insert into CommentRelate
values ('45', '2021.2.11','6841');


insert into CommentRelate
values ('75', '2021.2.18','5846');


insert into CommentRelate
values ('68', '2021.2.19','6144');


insert into Publish
values ('5846', 'Jacob');


insert into Publish
values ('6841', 'Jacob');


insert into Publish
values ('6144', 'Danny');


insert into Publish
values ('6985', 'Danny');

insert into Publish
values ('6985', 'BinMing');

insert into Publish
values ('6985', 'Jacob');

insert into Publish
values ('6985', 'James');

insert into Publish
values ('6985', 'Wanda');

insert into Publish
values ('6985', 'Dan');

insert into Publish
values ('6985', 'Faker');


commit;