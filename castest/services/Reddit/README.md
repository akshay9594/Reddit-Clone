mysql-ctl install
mysql-ctl cli

show databases;

use maindatabase;

CREATE TABLE parents (id INT NOT NULL, user VARCHAR(64), text TEXT, date DATETIME, code VARCHAR(10), num_like INT NOT NULL, num_dislike INT NOT NULL);
CREATE TABLE children (id INT NOT NULL, user VARCHAR(64), text TEXT, date DATETIME, par_code VARCHAR(10), child_like INT NOT NULL, child_dislike INT NOT NULL, child_code VARCHAR(10));
CREATE TABLE likedislike ( maincode VARCHAR(10), likenumber INT NOT NULL, dislikenumber INT NOT NULL, user VARCHAR(64));

