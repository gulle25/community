DELIMITER $$

DROP PROCEDURE IF EXISTS `add_comment`$$

CREATE PROCEDURE `add_comment`(IN i_contentno bigint, IN i_ownerno int, IN i_userid VARCHAR(32), IN i_nickname VARCHAR(32), IN i_target_nickname VARCHAR(32), IN i_comment text, in i_info varchar(4096))
BEGIN
  declare v_globalno bigint;
  declare v_commentno int;
  declare v_ownerno int;
  declare v_sequence int default 0;

  -- 최근 댓글 정보 읽기
  select ifnull(max(commentno) + 1, 1) from `comment` where contentno = i_contentno into v_commentno;

  if i_ownerno > 0 then
    -- 응답 댓글인 경우 sequence 값 설정
    select max(sequence) + 1 from `comment` where contentno = i_contentno and ownerno = i_ownerno into v_sequence;
  end if;
  set v_ownerno = if(i_ownerno > 0, i_ownerno, v_commentno);

  -- 댓글 추가
  insert into `comment` (contentno, ownerno, commentno, sequence, userid, nickname, target_nickname, `comment`, info, edit_time)
    values (i_contentno, v_ownerno, v_commentno, v_sequence, i_userid, i_nickname, i_target_nickname, i_comment, i_info, now());
  set v_globalno = last_insert_id();

  select 0 errno, v_globalno globalno, v_commentno commentno, v_ownerno ownerno, v_sequence sequence;
END$$

DELIMITER ;
