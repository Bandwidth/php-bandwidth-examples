SQLite format 3   @                                                                     -�   �    ���                                                                           �H--�CtableBasic Voice MailBasic Voice MailCREATE TABLE `Basic Voice Mail` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�E++�AtableVoice RemindersVoice RemindersCREATE TABLE `Voice Reminders` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�];;�QtableIncoming Call TransfersIncoming Call TransfersCREATE TABLE `Incoming Call Transfers` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�H--�CtableSMS Auto RepliesSMS Auto RepliesCREATE TABLE `SMS Auto Replies` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHA   
         / e�/                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 �%%�W7+12135147675+17192991226Hello, I am currently configuring my Catapult Auto Reply application, I will get back to you shortly.2015-Mar-Mon 20:03:54�%%�W7+12135147675+17192991226Hello, I am currently configuring my Catapult Auto Reply application, I will get back to you shortly.2015-Mar-Mon 19:58:33�%%�W7+12135147675+17192991226Hello, I am currently configuring my Catapult Auto Reply application, I will get back to you shortly.2015-Mar-Mon 19:16:30                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            �  ��m5 �                                                                           �H--�CtableBasic V                                                                              �H--�CtableSMS Auto RepliesSMS Auto RepliesCREATE TABLE `SMS Auto Replies` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�];;�QtableIncoming Call TransfersIncoming Call TransfersCREATE TABLE `Incoming Call Transfers` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�E++�AtableVoice RemindersVoice RemindersCREATE TABLE `Voice Reminders` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�H--�CtableBasic Voice MailBasic Voice MailCREATE TABLE `Basic Voice Mail` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )    \  \'�� X                                                                         �H--�CtableBasic ConferenceBasic ConferenceCREATE TABLE `Basic Conference` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�Q33�ItableAdvanced ConferenceAdvanced Conference	CREATE TABLE `Advanced Conference` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�H--�CtableKeypad SimulatorKeypad Simulator
CREATE TABLE `Keypad Simulator` (
        `from` VARCHAR(255),
        `to` VARCHAR(255),
        `meta` VARCHAR(255),
        `date` VARCHAR(255) 
      )�7==�tableAdvanced Conference DataAdvanced Conference DataCREATE TABLE `Advanced Conference Data` (
        `callFrom` VARCHAR(255),
        `receiverCallFrom` VARCHAR(255),
        `code` VARCHAR(255),
        `attended` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `digits` VARCHAR(255)
    )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           r r� � �                                                                                                                                                       �G77�-tableBasic Conference DataBasic Conference DataCREATE TABLE `Basic Conference Data` (
        `callFrom` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `callId` VARCHAR(255)
    )�	55�/tableVoice Reminders DataVoice Reminders DataCREATE TABLE `Voice Reminders Data` (
      `callId` VARCHAR(255),
      `recordingId` VARCHAR(255), 
      `title` VARCHAR(255),
      `speech` VARCHAR(255),
      `initiated` VARCHAR(255),
      `message` VARCHAR(255),
      `month` VARCHAR(255), 
      `thanks` VARCHAR(255),
      `year` VARCHAR(255),
      `date` VARCHAR(255)
    )�
77�#tableBasic Voice Mail DataBasic Voice Mail DataCREATE TABLE `Basic Voice Mail Data` (
       `callId` VARCHAR(255),
       `recordingId` VARCHAR(255),
       `mediaName` VARCHAR(255),
       `initiated` VARCHAR(255),
       `date` VARCHAR(255)
     )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             + xB�+                                                                                                                                                                                                                                                                                           �$--�{tableSIP Domains DataSIP Domains DataCREATE TABLE `SIP Domains Data` (
      `domain` VARCHAR(255),
      `from` VARCHAR(255),
      `to` VARCHAR(255)
    )�#==�YtableBaML Call Transfers DataBaML Call Transfers DataCREATE TABLE `BaML Call Transfers Data` (
      `verb` VARCHAR(255),
      `markup` VARCHAR(255)
    )�G77�-tableBasic Conference DataBasic Conference DataCREATE TABLE `Basic Conference Data` (
        `callFrom` VARCHAR(255),
        `conferenceId` VARCHAR(255),
        `callId` VARCHAR(255)
    )�;77�tableKeypad Simulator DataKeypad Simulator DataCREATE TABLE `Keypad Simulator Data` (
        `level` VARCHAR(255),
        `callId` VARCHAR(255),
        `key` VARCHAR(255)
    )                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            