-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 30, 2004 at 10:31 PM
-- Server version: 4.0.22
-- PHP Version: 4.3.9
-- 
-- Database: `php-crossword`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `words`
-- 

DROP TABLE IF EXISTS `words`;
CREATE TABLE `words` (
  `groupid` varchar(10) NOT NULL default '''lt''',
  `word` varchar(20) NOT NULL default '',
  `question` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`word`,`groupid`),
  KEY `groupid` (`groupid`),
  FULLTEXT KEY `word_3` (`word`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `words`
-- 

INSERT INTO `words` VALUES ('lt', 'SENEL�', 't�vo ar motinos, motina');
INSERT INTO `words` VALUES ('lt', 'TRAKAI', 'Vytauto Did�iojojo gimimo vieta');
INSERT INTO `words` VALUES ('lt', 'MERAS', 'miesto savivaldyb�s vadovas');
INSERT INTO `words` VALUES ('lt', 'RTR', 'Rusijos televizija');
INSERT INTO `words` VALUES ('lt', 'AMAS', '�adas, balsas');
INSERT INTO `words` VALUES ('lt', 'BANDA', 'Karvi� ...');
INSERT INTO `words` VALUES ('lt', 'SERBAS', 'Serbijos gyventojas');
INSERT INTO `words` VALUES ('lt', 'MTV', 'Muzikinis televizijos kanalas');
INSERT INTO `words` VALUES ('lt', 'KRA�IAI', 'miestelis Kelm�s rajone,');
INSERT INTO `words` VALUES ('lt', 'ETIKA', '�moni� elgesio normos');
INSERT INTO `words` VALUES ('lt', 'ASTRA', 'dar�elio g�l�');
INSERT INTO `words` VALUES ('lt', 'LO��', 'Mason� ...');
INSERT INTO `words` VALUES ('lt', 'DAUBA', 'Duob�, �dubimas, ...');
INSERT INTO `words` VALUES ('lt', 'SAMBA', 'brazil� kilm�s pramoginis  �okis');
INSERT INTO `words` VALUES ('lt', 'NORMA', 'nustatytas kiekis, dydis');
INSERT INTO `words` VALUES ('lt', 'LTSR', 'Lietuvos pavadinimas sovietme�iu');
INSERT INTO `words` VALUES ('lt', 'IKI', 'Parduotuvi� tinklas');
INSERT INTO `words` VALUES ('lt', 'VIETA', 'Susitikimo, nusikaltimo, gyvenamoji....');
INSERT INTO `words` VALUES ('lt', 'TRASA', 'linija, nu�ym�ta vietov�je arba �em�lapyje, nustatanti jud�jimo krypt�');
INSERT INTO `words` VALUES ('lt', 'MAESTRO', 'pagarbus �ymi� meninink� vadinimas');
INSERT INTO `words` VALUES ('lt', 'MATAS', 'pad�tis, kai �achuojamo karaliaus i�gelb�ti negalima');
INSERT INTO `words` VALUES ('lt', 'BARAS', 'restoranas, kur u�kand�iai ir g�rimai parduodami prie bufeto');
INSERT INTO `words` VALUES ('lt', 'VILNA', 'Avies ...');
INSERT INTO `words` VALUES ('lt', '�ARA', 'Vakaro ...');
INSERT INTO `words` VALUES ('lt', 'MENIU', 'Valgiara�tis');
INSERT INTO `words` VALUES ('lt', 'TAIKA', 'Ne karo metas');
INSERT INTO `words` VALUES ('lt', 'PK', 'Personalinis kompiuteris');
INSERT INTO `words` VALUES ('lt', 'ALFA', '�A� graiki�kai');
INSERT INTO `words` VALUES ('lt', 'JIDI�', '�yd� kalba');
INSERT INTO `words` VALUES ('lt', '�IAIP', 'Nei ..., nei taip');
INSERT INTO `words` VALUES ('lt', 'SIURBLYS', 'Dulki� surink�jas');
INSERT INTO `words` VALUES ('lt', 'BARBORA', '... Radvilait�');
INSERT INTO `words` VALUES ('lt', 'SAKALAS', 'Pl��rus pauk�tis');
INSERT INTO `words` VALUES ('lt', 'AZOTAS', 'chem. N');
INSERT INTO `words` VALUES ('lt', 'KALIGULA', 'Romos imperarorius');
INSERT INTO `words` VALUES ('lt', 'GREIT', '... gri�k');
INSERT INTO `words` VALUES ('lt', 'ALGA', 'Atlyginimas');
INSERT INTO `words` VALUES ('lt', 'DUONA', 'Miltinis valgis');
INSERT INTO `words` VALUES ('lt', 'A�ARA', 'poez.... dievo aky');
INSERT INTO `words` VALUES ('lt', 'ATGAL', 'Ne pirmyn');
INSERT INTO `words` VALUES ('lt', 'FRANK', 'Romanas " ... Kruk"');
INSERT INTO `words` VALUES ('lt', 'VGTU', 'Universitetas Vilniuje');
INSERT INTO `words` VALUES ('lt', 'PIETA', 'Mikelend�elo skulpt�ra');
INSERT INTO `words` VALUES ('lt', 'AURA', 'Energija supanti k�n�');
INSERT INTO `words` VALUES ('lt', 'NBA', 'Krep�inio asociacija');
INSERT INTO `words` VALUES ('lt', 'TOGA', 'Rom�ni�kas apsiaustas');
INSERT INTO `words` VALUES ('lt', 'PIGUS', 'Nebrangus');
INSERT INTO `words` VALUES ('lt', 'SAM', 'Ministerija');
INSERT INTO `words` VALUES ('lt', 'OPEL', 'Voki�kas automobilis');
INSERT INTO `words` VALUES ('lt', 'EMA', 'Emanuel� sutr.');
INSERT INTO `words` VALUES ('lt', 'ANTIS', 'Roko grup�');
INSERT INTO `words` VALUES ('lt', 'TARA', 'Stiklo ...');
INSERT INTO `words` VALUES ('lt', 'ROMA', 'Italijos sostin�');
INSERT INTO `words` VALUES ('lt', 'SAUGOS', '... pagalv�s');
INSERT INTO `words` VALUES ('lt', 'LAMPASAS', 'Prisiuvamas laipsnio �enklas');
INSERT INTO `words` VALUES ('lt', 'TURBO', '... dyzelinis variklis');
INSERT INTO `words` VALUES ('lt', 'J�ZUS', '... Kristus');
INSERT INTO `words` VALUES ('lt', 'X', 'Iksas');
INSERT INTO `words` VALUES ('lt', 'ROMEO', '... ir D�iuljeta');
INSERT INTO `words` VALUES ('lt', 'PETYS', 'anat. K�no dalis');
INSERT INTO `words` VALUES ('lt', 'SYSAS', 'seimo narys Algirdas ...');
INSERT INTO `words` VALUES ('lt', 'SKAMP', 'Muzikos grup�');
INSERT INTO `words` VALUES ('lt', 'PABAISA', 'Labai baisi');
INSERT INTO `words` VALUES ('lt', 'PARANGA', 'Parengimas');
INSERT INTO `words` VALUES ('lt', 'PRUSTAS', 'Ra�yt. Marselis  ...');
INSERT INTO `words` VALUES ('lt', 'PAMOKA', '45 min. mokykloje');
INSERT INTO `words` VALUES ('lt', 'PORYT', 'Po rytojaus');
INSERT INTO `words` VALUES ('lt', 'TORIS', 'chem. Th');
INSERT INTO `words` VALUES ('lt', 'U�VAKAR', 'Prie� dvi dienas');
INSERT INTO `words` VALUES ('lt', 'KARLAS', 'Buratino t�vas');
INSERT INTO `words` VALUES ('lt', 'SHARP', 'angl. -A�trus');
INSERT INTO `words` VALUES ('lt', 'RASOS', 'Pagoni�ka �vent�');
INSERT INTO `words` VALUES ('lt', 'SAMOA', 'Valst. Ramiajame vandenyne');
INSERT INTO `words` VALUES ('lt', 'SUBARU', 'Japoni�kas automobilis');
INSERT INTO `words` VALUES ('lt', 'L��U', 'Universitetas Kaune');
INSERT INTO `words` VALUES ('lt', 'GARAS', 'Vandens dujos');
INSERT INTO `words` VALUES ('lt', 'MARAS', 'A. Kamiu romanas');
INSERT INTO `words` VALUES ('lt', 'BVT', 'Bouvet sala');
INSERT INTO `words` VALUES ('lt', 'SIMAS', 'Babravi�iaus pseudonimas');
INSERT INTO `words` VALUES ('lt', 'KR�VA', '... malk�');
INSERT INTO `words` VALUES ('lt', 'SO', 'Somalis');
INSERT INTO `words` VALUES ('lt', 'PASAGA', 'Arklio batas');
INSERT INTO `words` VALUES ('lt', 'AB', 'Akcin� bendrov�');
INSERT INTO `words` VALUES ('lt', 'APUTIS', 'Ra�ytojas Juozas ...');
INSERT INTO `words` VALUES ('lt', 'A�', 'gram. 1 asmuo');
INSERT INTO `words` VALUES ('lt', 'BASAS', 'Be bat�');
INSERT INTO `words` VALUES ('lt', '�', 'Paskutin� raid�');
INSERT INTO `words` VALUES ('lt', 'KB', 'Kilobitai');
INSERT INTO `words` VALUES ('lt', '�AS', 'Populiari muz.grup�');
INSERT INTO `words` VALUES ('lt', 'A', 'Pirmoji raid�');
INSERT INTO `words` VALUES ('lt', 'LOS', 'Klubas Kaune �... Patrankos�');
INSERT INTO `words` VALUES ('lt', 'BAUDA', 'Pinigin� bausm�');
INSERT INTO `words` VALUES ('lt', 'REQUIEM', 'Mocarto k�rinys');
INSERT INTO `words` VALUES ('lt', 'RUSIJA', 'Valstyb�');
INSERT INTO `words` VALUES ('lt', 'J�ROJE', 'daina. �Palangos ... �');
INSERT INTO `words` VALUES ('lt', 'TB', 'chem.Terbis');
INSERT INTO `words` VALUES ('lt', 'BLU�NIS', 'anat. organas');
INSERT INTO `words` VALUES ('lt', 'BANANAS', 'Policininko lazda');
INSERT INTO `words` VALUES ('lt', 'BUR�', 'Burlaivio dalis');
INSERT INTO `words` VALUES ('lt', 'K�RYBA', 'Meninis procesas');
INSERT INTO `words` VALUES ('lt', 'LI�T�', 'Li�to motina');
INSERT INTO `words` VALUES ('lt', 'LUANDA', 'Angolos sostin�');
INSERT INTO `words` VALUES ('lt', 'KUR��', 'Kur�io moteris');
INSERT INTO `words` VALUES ('lt', 'UGNIS', 'Viena i� stichij�');
INSERT INTO `words` VALUES ('lt', 'NUOBOD�IAUJA', 'Nuobod�iai leid�ia laik�');
INSERT INTO `words` VALUES ('lt', 'AIB�', 'Daugyb�');
INSERT INTO `words` VALUES ('lt', 'RAJ', 'Rajonas sutr.');
INSERT INTO `words` VALUES ('lt', 'PENSN�', 'Senoviniai akiniai');
INSERT INTO `words` VALUES ('lt', 'I', 'Lotyni�kas vienetas');
INSERT INTO `words` VALUES ('lt', 'CV', 'Gyvenimo apra�ymas');
INSERT INTO `words` VALUES ('lt', 'MB', 'Megabaitai sutr.');
INSERT INTO `words` VALUES ('lt', 'AMEBA', 'zool. beformis');
INSERT INTO `words` VALUES ('lt', 'V', 'Lotyni�kas penketas');
INSERT INTO `words` VALUES ('lt', '�SESER�', 'Netikra sesuo');
INSERT INTO `words` VALUES ('demo', 'AFRICA', 'The world''s second-largest continent');
INSERT INTO `words` VALUES ('demo', 'LITHUANIA', 'One of the Baltic countries');
INSERT INTO `words` VALUES ('demo', 'VILNIUS', 'Capital of Lithuania');
INSERT INTO `words` VALUES ('demo', 'GREEN', 'Color');
INSERT INTO `words` VALUES ('demo', 'BICYCLE', 'A pedal-driven land vehicle');
INSERT INTO `words` VALUES ('demo', 'MOUNTAIN', 'A landform');
INSERT INTO `words` VALUES ('demo', 'SMARTY', 'Templating engine');
INSERT INTO `words` VALUES ('demo', 'ESTONIA', 'One of the Baltic countries');
INSERT INTO `words` VALUES ('demo', 'LATVIA', 'One of the Baltic countries');
INSERT INTO `words` VALUES ('demo', 'RIGA', 'Capital of Latvia');
INSERT INTO `words` VALUES ('demo', 'TALLINN', 'Capital of Estonia');
INSERT INTO `words` VALUES ('demo', 'ZEPPELIN', 'Airship');
INSERT INTO `words` VALUES ('demo', 'COW', 'Animal');
INSERT INTO `words` VALUES ('demo', 'DOG', 'Human''s best friend');
INSERT INTO `words` VALUES ('demo', 'CHEESE', 'A foodstuff made from the curdled milk');
INSERT INTO `words` VALUES ('demo', 'HELLO', 'Greeting');
INSERT INTO `words` VALUES ('demo', 'JAVA', 'Programming language');
INSERT INTO `words` VALUES ('demo', 'EARTH', 'Planet');
INSERT INTO `words` VALUES ('demo', 'AEROSMITH', 'A long-running hard rock band');
INSERT INTO `words` VALUES ('demo', 'MARS', 'The Rise And Fall Of Ziggy Stardust And The Spiders From...');
INSERT INTO `words` VALUES ('demo', 'GOOGLE', 'Search engine');
INSERT INTO `words` VALUES ('demo', 'LINUX', 'Operating system');
INSERT INTO `words` VALUES ('demo', 'BIX', 'Lithuanian rock band');
INSERT INTO `words` VALUES ('demo', 'BAGGINS', 'Bilbo ...');
