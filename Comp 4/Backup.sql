-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2015 at 03:44 PM
-- Server version: 5.1.57
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `a6325779_content`
--

-- --------------------------------------------------------

--
-- Table structure for table `AtoZ`
--

CREATE TABLE `AtoZ` (
  `idAtoZ` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `desc` text COLLATE latin1_general_ci,
  PRIMARY KEY (`idAtoZ`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=47 ;

--
-- Dumping data for table `AtoZ`
--

INSERT INTO `AtoZ` VALUES(1, 'Absence', 'If you are absent you must certificate your absences on line within 24hr of the absence. If this is not possible you must phone 278065 or email Absence@hillsroad.ac.uk on the first day of absence and inform the College of the reason why. All absence must be accounted for or it will be seen as unauthorised.');
INSERT INTO `AtoZ` VALUES(2, 'Alumni Association', 'Before leaving Hills Road, you will be encouraged to keep in touch with each other and with the College by joining the ''Hills Roadies''. More information is available via the ''Alumni'' button on the Home Page of the College''s website.');
INSERT INTO `AtoZ` VALUES(3, 'Bicycles', 'Should be locked securely in the cycle rack located next to The Rob Wilkinson Building. Access to the cycle rack is via the cycle path leading from Purbeck Road. Get off your bike on the site and walk it to and from the racks. If the cycle rack is full, please use Purbeck Road as an alternative. Both locations are covered by CCTV. Bicycles are vulnerable to theft, so they should be postcoded and you are advised not to bring expensive bicycles to College. It is strongly recommended that all cyclists wear cycle helmets.\r\n\r\n**It is not permissible to lock bicycles to the railings at the front of the College along Hills Road. Any bicycles found here will be removed by the caretakers which may necessitate cutting the locks at the owners'' expense.**');
INSERT INTO `AtoZ` VALUES(4, 'Bursary', 'This is the main place to pay for anything in College. You need your Hills Road College ID Card for all transactions. The Bursary is open between 8.30 to 9.15 am and 11.15 am to 4.30 pm Monday to Friday. It is in the main building at the front of the College along the corridor from Reception.');
INSERT INTO `AtoZ` VALUES(5, 'Buses', 'Any problems concerning buses to College should be referred to the Guidance Office.');
INSERT INTO `AtoZ` VALUES(6, 'Calender', 'The College calendar will be available to students and parents on the College website and intranet.');
INSERT INTO `AtoZ` VALUES(7, 'Cars', 'Students who travel to College by car **must first fill in a form which is available in the Guidance Office**. There is no parking for students on the College site (which includes Purbeck Road and the Sports Centre), and students should only travel to College in a car if absolutely necessary. An exception will be made for students who are registered disabled; if this applies to you, please contact your tutor to make the necessary arrangements. If you park in nearby side roads **please respect the rights of residents**. Students are warned not to park in Elsworth Place, opposite the College, since parking there is illegal.\r\n\r\nGenerally, the use of private cars to carry students on College business is not allowed. However, there may be extenuating circumstances in which it is sensible to use a private car to carry fellow students, in which case the following rules apply:\r\n\r\n- Students who drive fellow students in their own car are responsible for ensuring their passengers'' safety, that their vehicle is roadworthy, that they have an appropriate licence and that insurance cover for carrying the students is in place\r\n\r\n- The type of journeys envisaged includes taking fellow students to sports fixtures or on educational visits. (Any casualties requiring hospital treatment should be taken to hospital by taxi or ambulance, not by private vehicle)\r\n\r\n- If a student is to transport fellow students in their private vehicle they must complete and sign a certificate (available in the Bursary) before the journey can be undertaken\r\n');
INSERT INTO `AtoZ` VALUES(8, 'Digital Screens', 'At several key locations around College there are digital screens showing BBC 24 hour news alongside College news. Please check the screens for important notices of forthcoming events and opportunities.');
INSERT INTO `AtoZ` VALUES(9, 'Eating & Drinking', 'Water may be consumed anywhere in the College except in areas where there are computers and in language laboratories.\r\n\r\nSome teaching rooms are available for you to use during morning and lunch breaks. Signs are on the doors of these rooms indicating that they can be used as spaces to eat and drink at lunch or break time but not to eat hot food. It is your responsibility to keep these areas clean, tidy and free of litter. If you are not able to do this, then the rooms will no longer be made available for this purpose.');
INSERT INTO `AtoZ` VALUES(10, 'Café Direct', 'Open from 8.30 am to 8.30 pm (Fridays until 3pm) selling chips, curly fries, Pasta King, variety of snacks, cakes and pastries, cold and hot drinks. ');
INSERT INTO `AtoZ` VALUES(11, 'Hub', 'Open from 8.30 am until 3.00 pm selling baguettes, jacket potatoes, boxed salads, Costa coffee/speciality teas and snacks.');
INSERT INTO `AtoZ` VALUES(12, 'Link', 'Available for the consumption of meals brought onto site. Open from 8 am to 6.00 pm.');
INSERT INTO `AtoZ` VALUES(13, 'Email', 'You will have your own Hills Road email account. This will be used by staff and students alike to contact you directly and you them. You are expected to check your email account at least once a day in term time. You should respond to urgent College emails immediately, if possible, and always within 24 hours. Responses to non-urgent College emails should be completed within 48 hours. Staff will also check their email regularly during term time, but there can be no expectation of email responses from staff during evenings, weekends and holiday periods.');
INSERT INTO `AtoZ` VALUES(14, 'Examination Leave', 'The study leave arrangements for examinations that are scheduled outside the main summer examination period (e.g. November, January and early May) are as follows: \r\n\r\nFor A/AS/GCSE examinations, the leave entitlement is: on the day of the examination, the time before the examination starts, except for:\r\n\r\n- Drama & Theatre Studies and Performance Studies examinations, for which the entitlement will include the previous day. \r\n- Modern Languages oral examinations and Music practical examinations, for which the entitlement will be the single period (whether a teaching period or a study period) immediately before the time allocated for the examination. \r\n\r\nThere is no entitlement to study leave for university admissions tests. In all cases, students should return to their lessons at the end of the examination. The study leave arrangements for examinations that are scheduled during the main summer examination period (mid May to June) will be notified to students during the Spring Term.');
INSERT INTO `AtoZ` VALUES(15, 'Fees & Expenses', 'You will not have to pay any tuition fees if you are under 19 and have been resident within the European Union for the three years before your course. Different rules apply to students from outside the EU and students who are 19 or over at the start of their course. Full details are available from the Admissions Office.\r\n\r\nEssential textbooks and materials are provided free of charge, unless they are kept by the student after the course. Students are expected to pay towards the cost of field trips and visits, although the charges for these are kept to a minimum. Examination fees, with the exception of re-sits, are normally paid by the College.');
INSERT INTO `AtoZ` VALUES(16, 'Financial Help', 'For the academic year 2012-13, the College will receive a sum of money from the government for distribution to students who meet the criteria for financial support under the 16- 19 Bursary Scheme. Full details of eligibility and application procedures will be provided for students at enrolment in September 2012.');
INSERT INTO `AtoZ` VALUES(17, 'Fire Evacuation', 'Make sure you know how to get out of the building in the event of an emergency. This will be explained to you during your induction period by your tutor. Information notices are displayed in all rooms and corridors and other public places. If the alarm sounds you must obey instructions from staff, evacuate the building and assemble in the designated area.');
INSERT INTO `AtoZ` VALUES(18, 'First Aid', 'There are about twenty members of staff qualified in First Aid. To contact a first aider, see Ms Linda Pike in the Guidance Office or contact Reception. There is a medical room on the site: go to the Guidance Office to use the medical room.');
INSERT INTO `AtoZ` VALUES(19, 'Gap Year Grants', 'Each year The Association of Parents and Friends will provide funds to support College students who undertake a gap-year project following their final year at the College. Details of application procedures will be sent out to Year 13 students in the Spring Term.');
INSERT INTO `AtoZ` VALUES(20, 'Holidays and Planned Absences', 'If your parents wish you to go on an annual family holiday which can only be taken during termtime, you will need to ask your tutor at least a month in advance. They will give you the Planned Absence Request Form and explain what to do. Such arrangements should not be made unless it is completely unavoidable. Parents and students who ignore this reasonable request may be asked to see the Principal. Permission will not be granted to students requesting personal holidays during term. For other planned absences not organised by the College you will also need to use the Planned Absence Request Form: see tutor.');
INSERT INTO `AtoZ` VALUES(21, 'Independant Learning', 'Independent learning is a phrase that you will hear often during your time with us at the College. In a recent report by leading universities on what they wished that first year undergraduates could do better, top of their list were the skills associated with independent learning. This means being a good manager of your own learning and progress.\r\n\r\nLike universities, we are convinced that those students who develop the three Rs of:\r\n\r\n- **Resilience** (a willingness to keep trying)\r\n- **Resourcefulness** (a creative and flexible approach to learning: finding strategies to use when you haven''t been directed what to do)\r\n- **Reflection** (a self-awareness that allows you to analyse your progress and to take responsibility for improving it)\r\nequip themselves most effectively for A level learning challenges. Through progress reviews, assessment and feedback, tutor discussions and learning conversations with your teaching staff, there will be considerable focus on you developing effective independent learning strategies.');
INSERT INTO `AtoZ` VALUES(22, 'Illness', 'If you are taken ill during the day go to the Guidance Office where staff will help you if first aid is needed; check you can get home; fill in your absence on the register and hear from you or your parent/guardian when you get home. You may use the medical room to rest and recover until you can be collected, or you feel well enough to get yourself home or return to lessons.');
INSERT INTO `AtoZ` VALUES(23, 'IT - Acceptable Usage', 'Through signing the Acceptable Use of IT agreement, you have done your part in ensuring that those students who want to learn using the College''s network are enabled to do so.\r\n\r\nPlease be aware that activities, such as those listed below, will bring about a one week ban from the College''s network.  \r\n**Please do not:**\r\n\r\n- Send general emails to mass mailing lists\r\n- Play, store or distribute games (The Hub and The Link are designated as the only areas to play games on computers, should you wish)\r\n- Access inappropriate materials such as pornographic, racist or offensive material\r\n- Eat or drink near computers\r\n- Install software on College computers\r\n- Use someone else''s computer account or allow access to your account by others (including by leaving yourself logged on)\r\n- Move, tamper with or open computer equipment.\r\n\r\nIf caught in breach of the Acceptable Use of IT agreement, you and your tutor will receive an email giving a day''s notification of the one week ban from the College network. If you try to log on when you are banned, you will receive a pop up message that says: "Your account has time restrictions that prevent you from logging in at this time. Please try again later."');
INSERT INTO `AtoZ` VALUES(24, 'Library & Resources Centre', 'Normal opening hours are as follows:\r\n\r\n- **Monday** 08.30 - 17.30\r\n- **Tuesday** 08.30 - 17.30\r\n- **Wednesday** 08.30 - 17.30\r\n- **Thursday** 08.30 - 17.30\r\n- **Friday** 08.30 - 17.00\r\n\r\nHowever at key times of year the library will extend evening opening hours. Details will always be advertised in advance through e-mail, College News and the electronic sign boards. Your College card is your Library card. You will always need this to borrow items, including departmental textbooks. Full details of all Library services including the Library catalogue and our digital library can be found by clicking on the Library link on the student Sharepoint site.\r\n\r\n**The Library is not a social area.** It provides space for individual private study. To ensure the quality of the working environment is maintained students are expected to adhere to the Library code of conduct which includes:\r\n- Respecting the rights of others to work without disturbance\r\n- Bringing no food or drink except bottled water into the library or silent area\r\n- Ensuring electronic devices are used in silent mode only in the Library and are not the cause of disturbance to others (phones which disturb other people are liable to be confiscated until the end of the College day)\r\n- Disposing of litter in the bins provided\r\n- Making sure any books used are returned to the shelves where they were found');
INSERT INTO `AtoZ` VALUES(25, 'Lockers', 'A number of lockers will be available and are allocated each year through the Guidance Office.');
INSERT INTO `AtoZ` VALUES(26, 'Lost Property & Personal Possessions', 'You should look after your own property, for which the College can accept no responsibility. Please do not leave valuable articles unattended. The Bursary will provide a place of safe-keeping. The Music Department has special arrangements for the safe-keeping of musical instruments. Lockers are available for the storage of personal belongings when using the Sports Centre.\r\n\r\n**Any lost property which is found should be handed in to, and claimed from, the Resources Office near the Bursary.**');
INSERT INTO `AtoZ` VALUES(27, 'Medical Appointments', 'Unless you are attending a specialist clinic with restricted appointment times, you should make appointments with the doctor or dentist outside timetabled College commitments.');
INSERT INTO `AtoZ` VALUES(28, 'Mobile Phones', 'You should ensure that mobile phones or other electronic devices are switched off in all timetabled periods unless permission has been given by an individual staff member for their use in specific teaching and learning activities. Electronic devices can be used **in silent mode** only in the Library and must not be the cause of disturbance to others. Mobile phones may be used, with appropriate consideration to others, in social areas, dining areas and corridors. The Hub, The Atrium and the Library are wi-fi enabled and more will come on line over the year.');
INSERT INTO `AtoZ` VALUES(29, 'Motorcycles', 'Students are allowed to park motorcycles/scooters in the lay-by on Purbeck Road. Students who travel to College by motorcycle must first fill in a form which is available in the Guidance Office.');
INSERT INTO `AtoZ` VALUES(30, 'News Channe', 'You will receive news via your personalised portal page. In your IT induction, you will have been shown how to customise the news you receive to target it to your needs and interests.');
INSERT INTO `AtoZ` VALUES(31, 'Opening Hours', 'You may use the College premises between 8.00 am and 6.00 pm, Monday-Friday during term-time. Where appropriate you should follow safety instructions relating to specialist areas. If you are on College premises outside these hours you must be under the guidance of a member of staff who has agreed to be responsible and is present.\r\n\r\nDuring half term and holiday periods the College is normally closed to students.');
INSERT INTO `AtoZ` VALUES(32, 'Parents'' Consultations', 'Your parents are invited to meet your tutor and senior staff early in the autumn term of Year 12. They will have the opportunity to meet subject teachers in the Spring Term of Year 12 and the autumn term of Year 13.');
INSERT INTO `AtoZ` VALUES(33, 'Part-time Employment', 'We recognise that part-time employment can be beneficial for experience of work and also it can help financially. You should tell your tutor about part-time jobs and make sure that College work takes priority. Research has demonstrated that too much paid employment (e.g. more than around eight hours per week) is detrimental to academic achievement.');
INSERT INTO `AtoZ` VALUES(34, 'Posters & Notices', 'You may only display posters and notices on the student notice boards and should ask permission from the Student Guidance Officer to do so. You must not display unauthorised posters on internal or external walls in College.');
INSERT INTO `AtoZ` VALUES(35, 'Printing', 'You will receive an account for printing which you need to keep topped up to ensure you have sufficient printer credit. To top up your printer credit, please go to the Printing Credit link on the homepage of the student portal. **Colour printing charges are significantly more than black and white printing charges.**\r\n\r\nPlease be aware that printing any pages, whether colour or black and white, from a colour printer (printers with a suffix of -COL1 or -COL2) will be charged at the colour printing rate.');
INSERT INTO `AtoZ` VALUES(36, 'Organising Events', 'If you wish to organise an event (for example to promote a charity or in connection with a Student Society), please submit a request in writing to the Guidance Office; your request will be passed to the appropriate member of staff who will respond to you.');
INSERT INTO `AtoZ` VALUES(37, 'Quiet Room', 'The College has a room that may be used by students for prayer or quiet contemplation. Students wishing to use this facility should go to Reception where the Receptionist will explain how to use the room.');
INSERT INTO `AtoZ` VALUES(38, 'Silent Study Area', '(next to the Study Skills Department) is used for individual private study and is the only area of the college where total silence is always required.');
INSERT INTO `AtoZ` VALUES(39, 'Smoking', 'The serious risks to life and health of smoking are well-known, and the College urges any students who smoke to try to stop. Information and advice is available at http://nhs.uk and searching for smoking (quitting). In the College, there is information from the Guidance Office.\r\n\r\nFor safety reasons related to potential congestion, students are also asked not to smoke on the Hills Road pavement between Homerton Street and Purbeck Road. The same restriction applies to most of Purbeck Road (a private road owned by the College), except for a designated area just beyond the College car park entrance. Students can smoke in this area providing that they do so without causing a nuisance. __Please put stubs in the bin.__');
INSERT INTO `AtoZ` VALUES(40, 'Sports Facilities', 'The Sports & Tennis Centre offers the following facilities:\r\n\r\n- Multi-purpose Sports Hall\r\n- Fitness Room\r\n- Four Indoor Tennis Courts\r\n- Squash Court\r\n- Table Tennis Tables\r\n- Six Outdoor Tennis Courts \r\n- Projectile Hall (2 cricket lanes)\r\n\r\nStudents will be required to pay **£12.50 each year** (PE students pay £6 for 2 years), as a contribution to their centre membership. Having completed this annual payment, students will be able to book facilities free of charge from Monday to Friday during term time between the hours of 8.00 am - 5.30 pm. A student ID card should be presented at the Sports Centre Reception when completing a booking and must also be left at reception during the period of the booked session. Outside of College hours and during College holidays students pay members'' rates.\r\n\r\nThe student fee includes a free induction session to use the fitness room. This induction session is essential to ensure that equipment is used correctly and safely. Induction sessions can be booked at the Sports Centre Reception. Having completed the induction session, students will be able to access the fitness room (remember to hand your College ID card to the Sports Centre Reception and collect it after use).\r\n\r\nStudents are able to book the facilities seven days in advance. Students are subject to the conditions of cancellation for the facilities i.e. 48 hours notification of cancellation will not incur a charge. If you do not use the booked court you will be charged the booking fee as for fee-paying customers. Facilities can be booked from 7.00 am each day in advance of use.\r\n\r\nWhen using the Centre for any sporting activity you must wear suitable sports kit, including non-marking footwear.\r\n\r\n**Parking is not permitted in the Sports Centre car park during term time.**');
INSERT INTO `AtoZ` VALUES(41, 'Sports Ground - Luard Road', 'Has rugby and football pitches, and a cricket square. Access to the sports ground is under the supervision of the College sport staff. If you wish to use the sports field you must have permission from the Sports and Tennis Centre staff.');
INSERT INTO `AtoZ` VALUES(42, 'Student Social Areas', 'All members of the College should keep the Social Areas clean and tidy. Please put all litter in the bins and treat the furniture and fittings with care. The Hub and The Link have seating for social working or relaxation. They are open throughout the day. There is WI-FI access and computers you can use for social websites.\r\n\r\nThe __Student Council Office__ is in the Hub.\r\n\r\nThere is outside seating in many areas of the College. Students may sit on all grassed areas except in the Main Quad. If doing so during lesson time, please do not disturb nearby classes.');
INSERT INTO `AtoZ` VALUES(43, 'Student Voice', 'It is important that you have the opportunity to give us your views on all aspects of College life, and we hope that you feel sufficiently confident to do so. There are several ways in which your views can be heard:\r\n\r\n- Through your elected tutor group representative on the Student Council\r\n- Through elected members of the Student Council Executive, who meet regularly with staff\r\n- Through the elected student governor\r\n- Through student questionnaires or surveys\r\n- Directly to staff, particularly your tutor or the Assistant Principal (Support and Guidance), Mr. Nigel Taylor.\r\n\r\nThe findings from questionnaires and surveys and the action which the College intends to take in response will be summarised and published on the website.');
INSERT INTO `AtoZ` VALUES(44, 'Thefts', 'Thefts should be reported to the guidance office immediately.');
INSERT INTO `AtoZ` VALUES(45, 'Time Management', 'You are expected to be independent, to organise your time yourself and to make good use of the Library and Resources areas. You should aim to do about four hours independent study per week for each of your AS level courses. Subject teachers will set essays and various other tasks to be completed outside lesson time, and there will be additional reading, research and preparation in all subjects.\r\n\r\nSet written work and coursework will be expected by certain dates and you will have to plan your time carefully to meet the deadlines. Use the diary section of the Student Planner to help you with your planning. The transition from GCSE to advanced level is not an easy one, and you should ask for help if you need it. Departments hold lunchtime surgeries to help individual students with problems.');
INSERT INTO `AtoZ` VALUES(46, 'Work Experience', 'Must be organised in association with the Careers Department (if during College term-time)');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `idblog` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `alias` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `assoc_councillor` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `desc` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `image` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`idblog`),
  UNIQUE KEY `alias_UNIQUE` (`alias`),
  KEY `idcouncillors_idx` (`assoc_councillor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `blog`
--


-- --------------------------------------------------------

--
-- Table structure for table `colours`
--

CREATE TABLE `colours` (
  `idcolours` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `class` varchar(10) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idcolours`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `colours`
--

INSERT INTO `colours` VALUES(1, 'Dark Green', 'g');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `idcontact` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `assoc_councillor` int(10) unsigned DEFAULT NULL,
  `subject` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `message` text COLLATE latin1_general_ci NOT NULL,
  `complete` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idcontact`),
  KEY `idcouncillor_idx` (`assoc_councillor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `councillors`
--

CREATE TABLE `councillors` (
  `idcouncillors` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `shortname` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `role` int(10) unsigned DEFAULT NULL,
  `tutor` varchar(3) COLLATE latin1_general_ci DEFAULT NULL,
  `subjects` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `bio` text COLLATE latin1_general_ci,
  `image` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `sudo` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idcouncillors`),
  UNIQUE KEY `email` (`email`),
  KEY `idroles_idx` (`role`),
  KEY `tutor_idx` (`tutor`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `councillors`
--

INSERT INTO `councillors` VALUES(1, 'Gareth Nunns', 'Gareth', 'me@garethnunns.com', '$2.Fkz1inLpkA', 10, 'IKL', 'Maths, Physics & Computing', 'As part of the media subcommittee, I take photos and videos of events and also develop the website, [find out more about me](http://garethnunns.com)', '/img/profiles/councillor1.jpg', 1, 1);
INSERT INTO `councillors` VALUES(2, 'Alice French', 'Alice', 'AF19745@hillsroad.ac.uk', '$2CW4AEzyrLWU', 1, 'ADC', 'English Lit, History, Maths, Japanese', 'I support Stephen and help him lead on projects. I am always happy to hear from students about any issues so feel free to email me :)', '/img/profiles/councillor2.jpg', 0, 1);
INSERT INTO `councillors` VALUES(3, 'Stephen Watkins', 'Stephen', 'sw119948@hillsroad.ac.uk', '$2V7Y5RDimvcw', 2, 'ADC', 'Film Studies, Religious Studies and English Literature', 'Hi, I''m Stephen. I come up with policies to make sure your time at Hills Road is as pleasant as possible. If you have any ideas, feel free to email me!', '/img/profiles/councillor3.jpg', 0, 1);
INSERT INTO `councillors` VALUES(4, 'Ramganesh Lakshman', 'Ram', 'RL119108@hillsroad.ac.uk', '$2cS7HsNaYvF6', 8, 'IKL', 'English Literature, Maths, Chemistry, History', 'My job is to raise awareness and funds for a wide range of local and national charitable organisations. Email me if you have any charity ideas or want to start up a new charity project.', '/img/profiles/councillor4.jpg', 0, 1);
INSERT INTO `councillors` VALUES(5, 'Alex Moor', 'Alex', 'AM120106@hillsroad.ac.uk', '$2qBISPodjFMA', 7, 'GGT', 'Double Maths, Physics, Chemistry, French', 'I am in charge of keeping track of NUS issues and acting upon them, so if you have any concerns or issues you would like to raise in these areas please feel free to email me!', '/img/profiles/councillor5.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `councillors_roles`
--

CREATE TABLE `councillors_roles` (
  `idroles` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rolename` varchar(60) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idroles`),
  UNIQUE KEY `idroles_UNIQUE` (`idroles`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `councillors_roles`
--

INSERT INTO `councillors_roles` VALUES(1, 'Chair');
INSERT INTO `councillors_roles` VALUES(2, 'Vice Chair');
INSERT INTO `councillors_roles` VALUES(3, 'Secretary');
INSERT INTO `councillors_roles` VALUES(4, 'Treasurer');
INSERT INTO `councillors_roles` VALUES(5, 'Events');
INSERT INTO `councillors_roles` VALUES(6, 'Welfare, Equality and Diversity');
INSERT INTO `councillors_roles` VALUES(7, 'NUS');
INSERT INTO `councillors_roles` VALUES(8, 'Charities');
INSERT INTO `councillors_roles` VALUES(9, 'Head of Communications');
INSERT INTO `councillors_roles` VALUES(10, 'Webmaster');
INSERT INTO `councillors_roles` VALUES(11, 'Environment');
INSERT INTO `councillors_roles` VALUES(12, 'Societies');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `idfaqs` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `answer` text COLLATE latin1_general_ci,
  PRIMARY KEY (`idfaqs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `faqs`
--


-- --------------------------------------------------------

--
-- Table structure for table `form_reps`
--

CREATE TABLE `form_reps` (
  `idform_reps` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tutor` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `upper` tinyint(1) NOT NULL,
  `rep1` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `rep2` varchar(80) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`idform_reps`),
  KEY `tutor_idx` (`tutor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `form_reps`
--


-- --------------------------------------------------------

--
-- Table structure for table `functions`
--

CREATE TABLE `functions` (
  `idfunctions` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `desc` text COLLATE latin1_general_ci,
  `content` text COLLATE latin1_general_ci,
  PRIMARY KEY (`idfunctions`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `functions`
--

INSERT INTO `functions` VALUES(1, 'AtoZ', 'Output all of the A to Z items in alphabetical order', 'try {\r\n	$sql = "SELECT `name`,`desc` FROM AtoZ ORDER BY name";\r\n							\r\n	foreach ($dbh->query($sql) as $row) {\r\n		echo ''<h3>''.htmlentities($row[''name'']).''</h3>'';\r\n		echo write($row[''desc'']);\r\n	}\r\n}\r\ncatch (PDOException $e) {\r\n	echo $e->getMessage();\r\n}');
INSERT INTO `functions` VALUES(3, 'showImage(role)', 'Display image of councillor from role', 'try {\r\n	$sth = $dbh->prepare(''SELECT councillors.name, councillors.image\r\n		FROM councillors, councillors_roles\r\n		WHERE councillors_roles.rolename = ? \r\n		AND councillors_roles.idroles = councillors.role\r\n		AND councillors.active = 1 \r\n		LIMIT 1'');\r\n	$sth->bindValue(1, $role, PDO::PARAM_STR);\r\n	$sth->execute();\r\n	\r\n	$result = $sth->fetch(PDO::FETCH_OBJ);					\r\n	\r\n	echo ''<img src="''.$result->image.''" class="thumb" alt="''.$result->name.'' - ''.$role.''" />'';\r\n}\r\ncatch (PDOException $e) {\r\n	echo $e->getMessage();\r\n}');

-- --------------------------------------------------------

--
-- Table structure for table `gcb`
--

CREATE TABLE `gcb` (
  `idgcb` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`idgcb`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Global Content Blocks' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `gcb`
--

INSERT INTO `gcb` VALUES(1, 'head', '<meta name="robots" content="index, follow" />\r\n<meta http-equiv="content-type" content="text/html; charset=UTF-8" />\r\n<meta name="geo.position" content="52.188179;0.136028" />\r\n<meta name="geo.placename" content="Hills Road Sixth Form College, Cambridge, United Kingdom" />\r\n<meta name="geo.region" content="GB-CAM" />\r\n<link rel = "icon" type = "image/x-icon" href = "http://www.myhrsfc.co.uk/favicon.ico" />\r\n        \r\n<!-- \r\n	Design Copyright 2012, Andrew J. Watts\r\n	All Rights Reserved\r\n     \r\n	LEGAL DISCLAIMER:\r\n     \r\n	Between April 2014 and March 2015, Hills Road Sixth Form College and the Hills Road Student community are authorised to use this website, it''s content and it''s design(s).\r\n	From March 2015 this website will be FULL PROPERTY of the author, Andrew J Watts. Hills Road Sixth Form College will have no right or obligation to have access to or to use this website, it''s content and it''s design(s) from this date onwards unless agreed with any future Student Council.\r\n	END OF DISCLAIMER\r\n-->\r\n\r\n\r\n<!--[if lt IE 9]>\r\n	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>\r\n<![endif]-->\r\n<link rel="stylesheet" media="all" type="text/css" href="/css/style.css"/>\r\n<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0"/>\r\n\r\n<!-- JS -->\r\n\r\n<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>\r\n<script src="/js/css3-mediaqueries.js" type="text/javascript"></script>\r\n<script src="/site/custom.js" type="text/javascript"></script>\r\n<!-- not used? <script src="/js/tabs.js" type="text/javascript"></script> -->\r\n\r\n<!-- superfish: drop down menu -->\r\n<link rel="stylesheet" media="screen" type="text/css" href="/css/superfish.css" /> \r\n<script src="/js/superfish.js" type="text/javascript"></script>\r\n<script src="/js/hoverIntent.js" type="text/javascript"></script>\r\n<script src="/js/supersubs.js" type="text/javascript"></script>\r\n<!-- ENDS superfish -->\r\n\r\n<!-- prettyPhoto -->\r\n\r\n<link rel="stylesheet" href="/css/prettyPhoto.css" type="text/css" media="screen"> \r\n<script src="/js/jquery.prettyPhoto.js" type="text/javascript"></script>\r\n<!-- ENDS prettyPhoto -->\r\n\r\n<!-- poshytip -->\r\n<link rel="stylesheet" href="/css/tip-twitter.css" type="text/css">\r\n<link rel="stylesheet" href="css/tip-yellowsimple.css" type="text/css">\r\n<script src="/js/jquery.poshytip.min.js" type="text/javascript"></script>\r\n<!-- ENDS poshytip -->\r\n\r\n<!-- Flex Slider -->\r\n<link rel="stylesheet" href="/css/flexslider.css" type="text/css">\r\n<script src="/js/jquery.flexslider-min.js" type="text/javascript"></script>\r\n<!-- ENDS Flex Slider -->\r\n\r\n<!--[if IE 6]>\r\n<link rel="stylesheet" href="css/ie6-hacks.css" type="text/css" media="screen" />\r\n<script type="text/javascript" src="js/DD_belatedPNG.js"></script>\r\n	<script>\r\n  		/* EXAMPLE */\r\n  		DD_belatedPNG.fix(''*'');\r\n	</script>\r\n<![endif]-->\r\n\r\n<!-- Lessgrid -->\r\n<link rel="stylesheet" type="text/css" href="/css/lessgrid.css"/>\r\n\r\n<!-- Google Analytic -->\r\n<script>\r\n	(function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\r\n	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n	})(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');\r\n\r\n	ga(''create'', ''UA-53004474-2'', ''auto'');\r\n	ga(''send'', ''pageview'');\r\n</script>');
INSERT INTO `gcb` VALUES(2, 'social', '<div id="social-cont">\r\n	<div id="social-bar">\r\n		<ul>\r\n			<li><a href="http://www.facebook.com/myhrsfc" target="_blank" title="Facebook"><img src="/img/icons/facebook.png"  alt="Facebook" /></a></li>\r\n			<li><a href="mailto:studentcouncil13@hillsroad.ac.uk"  title="Email"><img src="/img/icons/email.png"  alt="Email" /></a></li>\r\n		</ul>\r\n	</div>\r\n</div>');
INSERT INTO `gcb` VALUES(4, 'sidewriting', '<!-- Sideways writing down side of content by Thomas Frodsham, 2013 -->\r\n<div id="hrscside">Hills Road Student Council 2014-2015</div>');
INSERT INTO `gcb` VALUES(3, 'footer', '<footer>\r\n	<div class="wrapper">\r\n	\r\n		<ul id="footer-cols">\r\n			\r\n			<li class="first-col">\r\n				\r\n				<div class="widget-block">\r\n					<h4>Quick Contact</h4>\r\n					<div class="recent-post">\r\n						<img src="//placehold.it/100" alt="Profile" class="thumb small" />\r\n						<h6>Chair''s name</h6>\r\n						<p><a href="#" target="_blank">Email the chair</a></p>\r\n					</div>\r\n					<div class="recent-post">\r\n						<img src="//placehold.it/100" alt="Profile" class="thumb small" />\r\n						<h6>Vice chair''s name</h6>\r\n						<p><a href="#" target="_blank">Email the vice chair</a></p>\r\n					</div>\r\n					<div class="recent-post">\r\n						<img src="//placehold.it/100" alt="Profile" class="thumb small" />\r\n						<h6>Webmaster''s name</h6>\r\n						<p><a href="#" target="_blank">Email the webmaster</a></p>\r\n					</div>\r\n				</div>\r\n\r\n			</li>\r\n			\r\n			<li class="second-col">\r\n				\r\n				<div class="widget-block">\r\n					<h4>Copyright</h4>\r\n					<p>Copyright &#169; <?php echo Date(''Y''); ?> Hills Road Sixth Form College Student Council</p>\r\n					<p>This site, its content and its designs are permitted for use only by the Hills Road Sixth Form College Student Council for 2014-2015.</p><!--You should probably ask AW if you want to use them-->\r\n					<p>Originally designed by <a href="http://www.andrew-watts.co.uk" target="_blank">Andrew Watts</a>, with JS from Luis Zuno</p>\r\n\r\n					<p>Modified by <a href="http://www.ely-web-design.co.uk/" target="_blank">Thomas Frodsham</a>, then by <a href="//garethnunns.com">Gareth Nunns</a></p>\r\n				</div>\r\n				\r\n			</li>\r\n			\r\n			<li class="third-col">\r\n				\r\n				<img src = "/img/site/studentcouncil.png" alt = "Student Council, Hills Road Sixth Form College" />\r\n				<a href = "http://www.hrsfc.ac.uk/"><img src = "/img/site/hrsfc.png" alt = "Hills Road Sixth Form College, Cambridge" /></a>\r\n				<p style = "text-align: center; font-size: 11px; line-height: 16px">This website is run solely by the Hills Road Sixth Form College Student Council. It does not reflect the views or opinions of Hills Road Sixth Form College.</p>\r\n\r\n			</li>	\r\n		</ul>				\r\n		<div class="clearfix"></div>\r\n		\r\n		\r\n	</div>\r\n	\r\n	<div id="to-top"></div>\r\n</footer>');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `idlinks` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `URL` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` tinyint(1) DEFAULT NULL,
  `idcolours` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idlinks`),
  KEY `idcolours_idx` (`idcolours`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `links`
--

INSERT INTO `links` VALUES(1, 'Email Welfare', 'bla@bla.com', 1, NULL);
INSERT INTO `links` VALUES(2, 'Example Link', '//garethnunns.com', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `nav`
--

CREATE TABLE `nav` (
  `idnav` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idparents` int(10) unsigned NOT NULL,
  `idpages` int(10) unsigned DEFAULT NULL,
  `idlinks` int(10) unsigned DEFAULT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`idnav`),
  KEY `idparents_idx` (`idparents`),
  KEY `idlinks_idx` (`idlinks`),
  KEY `idpages_idx` (`idpages`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `nav`
--

INSERT INTO `nav` VALUES(1, 2, 5, NULL, 1);
INSERT INTO `nav` VALUES(2, 2, NULL, 1, 2);
INSERT INTO `nav` VALUES(3, 2, NULL, 2, 3);
INSERT INTO `nav` VALUES(5, 3, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `idpages` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `subtitle` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `meta_title` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `special_head` text COLLATE latin1_general_ci,
  `body` text COLLATE latin1_general_ci NOT NULL,
  `sidebar` text COLLATE latin1_general_ci,
  `assoc_councillor` int(10) unsigned DEFAULT NULL,
  `desc` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `social_img` varchar(80) COLLATE latin1_general_ci DEFAULT NULL,
  `editor` tinyint(1) DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idpages`),
  UNIQUE KEY `idcontent_UNIQUE` (`idpages`),
  KEY `idcouncilllors_idx` (`assoc_councillor`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` VALUES(1, 'atoz', 'The Official Hills Road A to Z', 'A Short Guide to College', 'A to Z', NULL, 'Here''s *everything* you need to know about college\r\n\r\n{ AtoZ  }', NULL, NULL, 'The official Hills Road Student Council A-to-Z for new students', NULL, 1, 1);
INSERT INTO `pages` VALUES(2, '404', '404', 'Page not found :(', '', NULL, '### Dang it\r\n\r\nBasically, you''ve ended up here because the page you were looking for doesn''t exist or maybe hasn''t even been made yet. That or there is the chance that there''s a serious error on our server...\r\n\r\nEither way, we hope our minions (mainly our webmaster) will fix it soon.\r\n\r\nGo back to the [home page](/) for now?', 'If you think you shouldn''t be seeing this page, **please** contact our webmaster', 1, 'The requested page could not be found', NULL, 1, 1);
INSERT INTO `pages` VALUES(3, 'index', '', '', 'Home', '<script type="text/javascript">(function(d, s, id) {\r\n  var js, fjs = d.getElementsByTagName(s)[0];\r\n  if (d.getElementById(id)) return;\r\n  js = d.createElement(s); js.id = id;\r\n  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=187884137952301";\r\n  fjs.parentNode.insertBefore(js, fjs);\r\n}(document, ''script'', ''facebook-jssdk''));</script>', '<div class="flexslider home-slider">\r\n<ul class="slides">\r\n\r\n<li>\r\n<a href="//facebook.com/media/set/?set=a.726708264065094.1073741837.280290375373554" target="_blank">\r\n<img src="img/index/fresh2014.jpg" alt="Freshers 2014" />\r\n</a>\r\n</li>\r\n\r\n<li>\r\n<a href="http://www.facebook.com/myhrsfc" target="_blank">\r\n<img src="img/index/facebook.png" alt="Facebook" />\r\n</a>\r\n</li>\r\n\r\n<li>\r\n<img src="img/index/chargers.png" alt="Phone Chargers" />\r\n</li>\r\n\r\n<li>\r\n<a href="http://www.twitter.com/myhrsfc" target="_blank">\r\n<img src="img/index/twitter.png" alt="Find us on Twitter" />\r\n</a>\r\n</li>\r\n\r\n</ul>\r\n</div>\r\n<div class="shadow-slider"></div>\r\n\r\n<div class="headline facebook">\r\n				\r\n<div class="fb-like" data-href="http://www.facebook.com/myhrsfc" data-send="true" data-width="750" data-show-faces="true" data-font="arial"></div>\r\n\r\n</div>\r\n\r\n<div id="fb-root"></div>\r\n\r\n<div class="page-content hasaside">\r\n\r\n{ showImage(Chair) }\r\n\r\n<h1>Welcome!</h1>\r\n\r\n<h3>A welcome from your Student Council</h3>\r\n\r\n<p>Welcome to the Student Council website! Our job is to provide a mix of representations, activities and services to the students of Hills Road Sixth Form College. We are not for profit, so any money we make through our activities is ploughed straight back into providing you with more and better services.</p>\r\n\r\n<p>We look after all of the societies on campus. We are governed by our Constitution, which outlines how we operate and what we do. The Council is managed by a collective of 11 officers, who work together with form representatives on a regular basis. Meet the council here</p>\r\n\r\n<p>Please check this site regularly.</p>\r\n\r\n<aside>\r\n<h4>Quick Links</h4>\r\n<ul>\r\n<li><a href="/policies.html">Policy Tracker</a></li>\r\n<li><a href="/who.html">Meet the Council</a></li>\r\n<li><a href="/acf.html">Anonymous Contact Form</a></li>\r\n<li><a href="welcome">Lower Sixth/Prospective Students</a></li>\r\n</ul>\r\n\r\n<a href="welcome"><img src="/img/2014/group.jpg" /></a>\r\n</aside>\r\n\r\n</div>', '', NULL, 'Stay up to date and in the loop with the official website from the HRSFC, Hills Road Sixth Form College, Student Council', NULL, 0, 1);
INSERT INTO `pages` VALUES(6, 'welcome', 'Welcome', 'An introduction from your Council', 'Welcome, Lower 6th!', NULL, '<img src="/img/2014/group.jpg" />\r\n\r\nHey lower 6th - welcome to Hills! The first few weeks can be a bit daunting so we''ve put together a handy section on the site to ensure the process is as stress-free as possible for everyone involved.\r\n\r\nHills Road is an energetic, exciting place with a limitless opportunities for you to engage in, so make sure that you make full use of the range of societies and clubs we''ve worked hard to provide in the coming year.\r\n \r\nIf you want more information about what we do please feel free to look through the rest of this website which is specifically designed to get the most important information to you in a quick manner.\r\n\r\nIf you''ve any questions at all please email your Chair, Alice French or  the entire council. You can also contact us anonymously using our online contact form. We''re ready on standby to reply to any queries you may have. \r\n\r\nThanks for reading and have fun in the year ahead!!\r\n\r\nYour Student Council, 2015', '', 2, 'A warm welcome from the Hills Road Student Council to new and prospective students', NULL, 1, 1);
INSERT INTO `pages` VALUES(4, 'support', 'Support & Welfare', 'Helping you along the way', '', NULL, '### Help is at hand!\r\n\r\nEllie is responsible for welfare, diversity and equality at Hills, and is here to support you! From the trivial to more serious matters, you''re able to confidentially discuss anything at all with Ellie. If you have any problems at all, then please do click below to email her. Or if you prefer to contact us anonymously, find the link just below.\r\n\r\n- [Email the Welfare Officer](mailto:welfare@officer.com)\r\n- [Anonymous Contact Form](/acf)\r\n- http://stonewall.org.uk (LGBT)\r\n- http://ditchthelabel.org (Anti-bullying)\r\n- http://equalityhumanrights.com\r\n- http://interfaith.org.uk\r\n- http://disabledgo.com\r\n- http://syacambs.org (local LGBT group)\r\n- http://mind.org.uk (mental health charity)\r\n- http://mindincambs.org.uk (local mental health group)\r\n- http://scope.org.uk\r\n- http://mindfull.org\r\n\r\nIf it takes your fancy, why not check out this [Student Welfare site on Direct.gov](http://www.direct.gov.uk/en/YoungPeople/DG_10016099)?', NULL, NULL, 'Support and Welfare at Hills Road Sixth Form College', NULL, 1, 1);
INSERT INTO `pages` VALUES(5, 'equality', 'Equality & Diversity', NULL, '', NULL, '##### Official documents published by Hills Road regarding the Equality and Diversity of the College:\r\n\r\n- [HRSFC equality & diversity](http://www.hrsfc.ac.uk/equalityDiversity.aspx)\r\n- [The equality and diversity report on HRSFC (2011-2012)](http://myhrsfc.co.uk/docs/ED_Report_for_Corporation_2012.docx)\r\n- [Single Equality Scheme HRSFC (2010-2013)](http://myhrsfc.co.uk/docs/Single_Equality_Scheme.doc)\r\n- [The SES (Single Equality Scheme) HRSFC action plan (2011-2012)](http://www.hrsfc.ac.uk/SESActionPlan2011_12.pdf)\r\n- [The SES (Single Equality Scheme) HRSFC action plan REVIEW (Oct 2012)](http://myhrsfc.co.uk/docs/SES_Action_Plan_2011-12_-_Review.doc)\r\n\r\nIf you have any specific questions relating to the equality and diversity of the college, then please do contact us.', NULL, NULL, 'Equality and Diversity information for Hills Road Sixth Form College', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `idparents` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpages` int(10) unsigned NOT NULL,
  `name` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `subheader` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `position` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `special` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idparents`),
  KEY `idpages_idx` (`idpages`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` VALUES(1, 3, 'MyHRSFC', 'welcome', '1', 0);
INSERT INTO `parents` VALUES(2, 4, 'support', 'here to help', '2', 0);
INSERT INTO `parents` VALUES(3, 6, 'Lower 6th', 'all the info', '8', 1);

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `idpolicies` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assoc_councillor` int(10) unsigned DEFAULT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `desc` text COLLATE latin1_general_ci,
  `progress` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpolicies`),
  KEY `idcouncillors_idx` (`assoc_councillor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `policies`
--


-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `year` int(11) NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `specialhead` text COLLATE latin1_general_ci,
  `image` varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `societies`
--

CREATE TABLE `societies` (
  `idsocieties` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `society` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `desc` text COLLATE latin1_general_ci,
  PRIMARY KEY (`idsocieties`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `societies`
--


-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `initials` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`initials`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` VALUES('IKL', 'Mr Lee');
INSERT INTO `tutors` VALUES('ADC', 'Mr Cumming');
INSERT INTO `tutors` VALUES('GGT', 'Mr Taylor');
INSERT INTO `tutors` VALUES('RZK', 'Mrs Kavanagh');