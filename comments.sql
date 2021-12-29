CREATE TABLE `blog`.`comments` ( 
 `id` INT NOT NULL AUTO_INCREMENT ,
 `comment` VARCHAR(250) NOT NULL ,
 `post_id` INT NOT NULL ,
 `user_id` INT NOT NULL ,
 PRIMARY KEY (`id`), INDEX(`post_id`), INDEX(`user_id`)
) ENGINE = InnoDB;


ALTER TABLE `comments` ADD CONSTRAINT `post_id_comment_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 

ALTER TABLE `comments` ADD CONSTRAINT `user_id_comment_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
