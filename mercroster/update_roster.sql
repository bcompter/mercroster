---
---Add call sign column to crew table
---
ALTER TABLE `crew` ADD COLUMN `callsign` varchar(30);

---
---Add troid column to technicalreadouts
---
ALTER TABLE `equipment` ADD COLUMN `troid` int(10);