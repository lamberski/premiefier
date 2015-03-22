DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `email` TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS `premieres`;
CREATE TABLE `premieres` (
  `id` INTEGER NOT NULL,
  `released_at` INTEGER NOT NULL,
  PRIMARY KEY(id)
);

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER NOT NULL,
  `premiere_id` INTEGER NOT NULL,
  UNIQUE(`user_id`, `premiere_id`)
);
