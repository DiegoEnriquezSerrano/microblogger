const nav = _q('#navList');
const topOfNav = [nav.offsetTop];
const originalTopOfNav = nav.offsetTop;
const bioTextHeight = _q('#user_bio_text').offsetHeight;

function fixNav() {
  if (_q('#user_bio').className != "active") {
    topOfNav[0] = originalTopOfNav - bioTextHeight;
  } else {
    topOfNav[0] = originalTopOfNav;
  }
  if (window.scrollY >= topOfNav[0]) {
    _q('#postsModule').style.paddingTop = nav.offsetHeight + 'px';
    document.body.classList.add('fixed-nav');
  } else {
    _q('#postsModule').style.paddingTop = 0;
    document.body.classList.remove('fixed-nav');
  }
}

window.addEventListener('scroll', fixNav);