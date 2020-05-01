if (!paths[1] || (paths[1] != 'following' && paths[1] != 'followers' && paths[1] != 'mutuals')) {
  getDirectoryLinks[0].classList.toggle('active');
} else if (paths[1] == 'following') {
  getDirectoryLinks[1].classList.toggle('active');
} else if (paths[1] == 'followers') {
  getDirectoryLinks[2].classList.toggle('active');
} else if (paths[1] == 'mutuals') {
  getDirectoryLinks[3].classList.toggle('active');
}