create table conversation_images(image_id int auto_increment primary key not null, convo_id int not null, foreign key (convo_id) references conversations(convo_id), image_dir varchar(250) not null);
