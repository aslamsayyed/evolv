INSERT INTO `user` VALUES (1,'Aslam','aslam@mutant-tech.com','e10adc3949ba59abbe56e057f20f883e',NULL,NULL,'2014-01-15 19:17:29',NULL),(2,'Rajesh','rajesh.jagtap@mutant-tech.com','e10adc3949ba59abbe56e057f20f883e',NULL,'2014-01-15 19:15:35','2014-01-15 19:15:35',NULL);

INSERT INTO `project` VALUES (1,'evolv','Develop simple task management system.',2,NULL,NULL,'2014-01-15 19:17:20',NULL);

INSERT INTO `sprint` VALUES (1,'Prototype','2014-01-13','2014-01-17','Develop working prototype',NULL,1,2,'2014-01-15 19:24:56','2014-01-15 19:24:56',NULL);

INSERT INTO `task` VALUES (1,'Crud for entities','using angular, tonic & bootstrap develop crud for all entities',13,1,3,2,1,1,NULL,2,'2014-01-15 19:30:53','2014-01-16 12:50:49',NULL,2,'2014-01-16 12:50:49',NULL,NULL),(2,'Crud - User',NULL,3,5,3,2,1,1,1,1,'2014-01-15 19:31:39','2014-01-16 12:51:25',NULL,2,'2014-01-16 12:51:25',NULL,NULL),(3,'Crud - Project',NULL,3,5,3,2,1,1,1,2,'2014-01-15 19:32:43','2014-01-16 12:51:49',NULL,2,'2014-01-16 12:51:49',NULL,NULL),(4,'Crud - Sprint',NULL,3,5,3,2,1,1,1,3,'2014-01-15 19:34:44','2014-01-16 12:51:58',NULL,2,'2014-01-16 12:51:58',NULL,NULL),(5,'Crud - Task',NULL,7,5,3,2,1,1,1,4,'2014-01-15 19:35:11','2014-01-16 12:52:11',NULL,2,'2014-01-16 12:52:11',NULL,NULL),(6,'Crud - Tag',NULL,2,5,3,2,1,1,1,5,'2014-01-15 19:35:48','2014-01-16 12:52:22',NULL,2,'2014-01-16 12:52:22',NULL,NULL);

INSERT INTO `tag` VALUES (1,'feature',NULL,NULL,NULL),(2,'bug','2014-01-15 19:08:50','2014-01-15 19:08:50',NULL),(3,'ui-design','2014-01-15 19:09:04','2014-01-15 19:10:26',NULL),(4,'code-review','2014-01-15 19:09:59','2014-01-15 19:12:03',NULL),(5,'training','2014-01-15 19:10:10','2014-01-15 19:10:10',NULL),(6,'specification','2014-01-15 19:10:34','2014-01-15 19:12:17',NULL),(7,'analysis','2014-01-15 19:11:00','2014-01-15 19:11:00',NULL),(8,'go-through','2014-01-15 19:11:17','2014-01-15 19:11:17',NULL),(9,'testing','2014-01-15 19:13:11','2014-01-15 19:13:11',NULL);

INSERT INTO `tasktag` VALUES (1,1,NULL,NULL,NULL);
