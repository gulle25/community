DELIMITER $$

DROP PROCEDURE IF EXISTS `add_content`$$

CREATE PROCEDURE `add_content`(IN i_cafeid VARCHAR(32), IN i_boardid VARCHAR(32), IN i_ownerno int, IN i_userid VARCHAR(32), IN i_nickname VARCHAR(32), IN i_target_nickname VARCHAR(32), IN i_title VARCHAR(256), IN i_content text, in i_info varchar(4096))
label_procedure: BEGIN
  declare v_globalno bigint;
  declare v_contentno int;
  declare v_ownerno int;
  declare v_sequence int default 0;

  -- 최근 게시물 정보 읽기
  select ifnull(max(contentno) + 1, 1) from content where cafeid = i_cafeid into v_contentno;

  if i_ownerno > 0 then
    -- 응답 게시물인 경우 sequence 값 설정
    select max(sequence) + 1 from content where cafeid = i_cafeid and ownerno = i_ownerno into v_sequence;
  end if;
  set v_ownerno = if(i_ownerno > 0, i_ownerno, v_contentno);

  -- 게시물 추가
  insert into content (cafeid, boardid, contentno, ownerno, sequence, userid, nickname, target_nickname, title, content, info, edit_time)
    values (i_cafeid, i_boardid, v_contentno, v_ownerno, v_sequence, i_userid, i_nickname, i_target_nickname, i_title, i_content, i_info, now());
  set v_globalno = last_insert_id();

  select 0 errno, v_globalno globalno, v_contentno contentno, v_ownerno ownerno, v_sequence sequence;
END$$

DELIMITER ;
