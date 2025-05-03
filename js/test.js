const roles = ["admin", "vip", "bannir", "normal"];
roles.forEach(function (item, index, array) {
  if (index == roles.length - 1) {
    console.log(roles[0]);
  } else {
    console.log(roles[index + 1]);
  }
});
