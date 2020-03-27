if (secondSplit[0] == 'timeline') {
  getDirectoryLinks[0].classList.toggle('active');
} else if (secondSplit[0] == 'published') {
  getDirectoryLinks[1].classList.toggle('active');
} else if (secondSplit[0] == 'drafts') {
  getDirectoryLinks[2].classList.toggle('active');
} else if (secondSplit[0] == 'liked') {
  getDirectoryLinks[3].classList.toggle('active');
}