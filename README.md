# 2022-PHPgram
2022.03.15부터 수강 중인 K-디지털트레이닝 기업 요구를 반영한 PHP 풀스택 개발자 양성과정 중 PHP MVC패턴을 이용한 인스타그램 클론코딩 실습 파일입니다. <br>
강사님 깃헙: https://github.com/sbsteacher/PHPgram
# composer 설치
  - https://techhans.tistory.com/57
  - http://getcomposer.org/download


# ratchet 라이브러리 설치
  composer require cboden/ratchet

# composer.json, autoload 적용
  
  composer dump-autoload




# 웹소켓 서버 실행 (CLI에서)
  php socketRun.php




# 웹소켓 서버 실행시 xdebug 에러 발생 시
  
  (php.ini 파일 내용 중, 아래 부분 주석 처리로 해결)
  ;zend_extension=xdebug
