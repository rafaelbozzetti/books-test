<?php

return array(
    'users' => array(
      'create' => 'CREATE TABLE if not exists users (
                      id INT NOT NULL AUTO_INCREMENT ,
                      username VARCHAR(200) NOT NULL ,
                      password VARCHAR(250) NOT NULL ,
                      role VARCHAR(20) NULL ,
                      PRIMARY KEY (id) )
                    ENGINE = InnoDB;' ,
      'drop'   => 'drop table users;',
    ),
    'books' => array(
        'create' => 'CREATE  TABLE if not exists books (
                      id INT NOT NULL AUTO_INCREMENT ,
                      user_id INT NOT NULL ,
                      title VARCHAR(250) NOT NULL ,
                      description TEXT NOT NULL ,
                      author VARCHAR(250) NOT NULL ,
                        PRIMARY KEY (id, user_id) ,
                        INDEX fk_books_users (user_id ASC) ,
                      CONSTRAINT fk_books_users
                        FOREIGN KEY (user_id )
                            REFERENCES users (id )
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION)
                    ENGINE = InnoDB;',
        'drop' =>'drop table books;'
    ),
);