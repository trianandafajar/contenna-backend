var bdaymonthInput = document.getElementById('month');
var currentDate = new Date();
var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
var currentYear = currentDate.getFullYear().toString();
bdaymonthInput.value = `${currentYear}-${currentMonth}`;
