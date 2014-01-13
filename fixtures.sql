insert into `User` (name, email, password) values ('Aslam Sayyed', 'aslam.sayyed@gmail.com', md5('123456'));
insert into `Project` (name, description, user_id) values ('evolv', 'evolv by mutant tech', 1);
insert into `sprint` (name, description, user_id, project_id, start_date, end_date, retrospective) values ('get on feet', 'Current sprint', 1, 1, CURRENT_DATE, CURRENT_DATE+14, 'After end of the scrum owner should do it');
insert into `task` (name, description, user_id, project_id, start_date, end_date, retrospective) values ('get on feet', 'Current sprint', 1, 1, CURRENT_DATE, CURRENT_DATE+14, 'After end of the scrum owner should do it');
INSERT INTO `evolvdb`.`task`(`id`,`name`,`description`,`points`,`status`,`user_id`,`project_id`,`sprint_id`)VALUES(1, 'Sample task', 'Task description', 2, 5, 1, 1, 1);
INSERT INTO `evolvdb`.`tag`(`id`,`name`) values(1, "feature");
INSERT INTO `evolvdb`.`tasktag`(`task_id`,`tag_id`) values(1, 1);
