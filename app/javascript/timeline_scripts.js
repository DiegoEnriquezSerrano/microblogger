if (paths[0] == 'timeline') {
  getDirectoryLinks[0].classList.toggle('active');
} else if (paths[0] == 'published') {
  getDirectoryLinks[1].classList.toggle('active');
} else if (paths[0] == 'drafts') {
  getDirectoryLinks[2].classList.toggle('active');
} else if (paths[0] == 'liked') {
  getDirectoryLinks[3].classList.toggle('active');
}