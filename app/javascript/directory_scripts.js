const getDirectoryLinks = _qs('.nav-link');
let firstSplit = window.location.href.split('http://localhost/microblogger/');
let secondSplit = firstSplit[1].split('/');

if (!secondSplit[1] || (secondSplit[1] != 'following' && secondSplit[1] != 'followers' && secondSplit[1] != 'mutuals')) {
  getDirectoryLinks[0].classList.toggle('active');
} else if (secondSplit[1] == 'following') {
  getDirectoryLinks[1].classList.toggle('active');
} else if (secondSplit[1] == 'followers') {
  getDirectoryLinks[2].classList.toggle('active');
} else if (secondSplit[1] == 'mutuals') {
  getDirectoryLinks[3].classList.toggle('active');
}