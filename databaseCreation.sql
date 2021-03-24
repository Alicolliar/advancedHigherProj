CREATE DATABASE testdb; ---Create Database
USE testdb;

CREATE TABLE elects ( ---Create the "elects" table
  electID tinyint auto_increment primary key,
  question VARCHAR(25),
  type VARCHAR(11),
  dateTimeBeginning datetime,
  dateTimeEnding datetime,
  opt1 VARCHAR(25),
  opt2 VARCHAR(25),
  opt3 VARCHAR(25),
  opt4 VARCHAR(25),
  opt1Votes int,
  opt2Votes int,
  opt3Votes int,
  opt4Votes int,
  running tinyint
);

CREATE TABLE users ( ---Create the "elects" table
  userID tinyint auto_increment primary key,
  userName VARCHAR(25),
  pwd VARCHAR(25),
  userLevel tinyint,
  voted tinyint
)

INSERT INTO users (userName, pwd, userLevel, voted) VALUES ("defaultUser", "P4ssw0rd", 1, 0); ---Inserts a default user, "defaultUser", so that the user doesn't have to add their own user by hand
