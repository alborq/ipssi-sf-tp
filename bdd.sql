#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE user(
        id     Int  Auto_increment  NOT NULL ,
        email  Varchar (255) NOT NULL ,
        pwd    Varchar (255) NOT NULL ,
        level  Int NOT NULL ,
        amount Int NOT NULL
	,CONSTRAINT user_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: article
#------------------------------------------------------------

CREATE TABLE article(
        id           Int  Auto_increment  NOT NULL ,
        title        Varchar (255) NOT NULL ,
        content      Longtext NOT NULL ,
        comment      Bool NOT NULL ,
        date_article Datetime NOT NULL ,
        id_user      Int NOT NULL
	,CONSTRAINT article_PK PRIMARY KEY (id)

	,CONSTRAINT article_user_FK FOREIGN KEY (id_user) REFERENCES user(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: comment
#------------------------------------------------------------

CREATE TABLE comment(
        id           Int  Auto_increment  NOT NULL ,
        content      Longtext NOT NULL ,
        date_comment Date NOT NULL ,
        id_user      Int NOT NULL ,
        id_article   Int NOT NULL
	,CONSTRAINT comment_PK PRIMARY KEY (id)

	,CONSTRAINT comment_user_FK FOREIGN KEY (id_user) REFERENCES user(id)
	,CONSTRAINT comment_article0_FK FOREIGN KEY (id_article) REFERENCES article(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: game
#------------------------------------------------------------

CREATE TABLE game(
        id     Int  Auto_increment  NOT NULL ,
        amount Int NOT NULL ,
        gain   Int NOT NULL
	,CONSTRAINT game_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: bet
#------------------------------------------------------------

CREATE TABLE bet(
        id      Int  Auto_increment  NOT NULL ,
        amount  Int NOT NULL ,
        gain    Int NOT NULL ,
        id_user Int NOT NULL ,
        id_game Int NOT NULL
	,CONSTRAINT bet_PK PRIMARY KEY (id)

	,CONSTRAINT bet_user_FK FOREIGN KEY (id_user) REFERENCES user(id)
	,CONSTRAINT bet_game0_FK FOREIGN KEY (id_game) REFERENCES game(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: jouer
#------------------------------------------------------------

CREATE TABLE jouer(
        id      Int NOT NULL ,
        id_user Int NOT NULL
	,CONSTRAINT jouer_PK PRIMARY KEY (id,id_user)

	,CONSTRAINT jouer_game_FK FOREIGN KEY (id) REFERENCES game(id)
	,CONSTRAINT jouer_user0_FK FOREIGN KEY (id_user) REFERENCES user(id)
)ENGINE=InnoDB;
