function isNumberKey(evt) {

    var charCode = (evt.which) ? evt.which : event.keyCode
    var isnumeric = false;
    if (charCode >= 48 && charCode <= 57)
        isnumeric = true;
    if (charCode == 46)
        isnumeric = true;
    if (charCode == 8)
        isnumeric = true;
    if (charCode == 9)
        isnumeric = true;
    if (charCode == 110)
        isnumeric = true;
    if (charCode == 190)
        isnumeric = true;
    if (charCode >= 37 && charCode <= 40)
        isnumeric = true;
    if (charCode >= 96 && charCode <= 105)
        isnumeric = true;

    return isnumeric;

}


function isAlphabatKey(evt) {

  var charCode = (evt.which) ? evt.which : event.keyCode
  var isnumeric = false;
  if (charCode >= 65 && charCode <= 90)
      isnumeric = true;
  if (charCode == 57)
      isnumeric = true;
  if (charCode == 48)
      isnumeric = true;
  if (charCode == 189)
      isnumeric = true;
  if (charCode == 55)
      isnumeric = true;
  if (charCode == 8)
      isnumeric = true;
  if (charCode == 46)
      isnumeric = true;
  if (charCode == 222)
      isnumeric = true;
  if (charCode == 17)
      isnumeric = false;
  if (charCode == 32)
      isnumeric = true;
  if (charCode == 9)
      isnumeric = true;
  return isnumeric;

}

// checkfieldValidation 
function checkFieldValidation(param) 
{
    var val = $(param).val();
    if(val.length>0){
        $(param).siblings(".validation-message").hide();
    }else{
        $(param).siblings(".validation-message").show();
    }
    
}

function formatCNIC(input) 
{
    // Remove any existing dashes and non-numeric characters
    let cnic = input.value.replace(/[^0-9]/g, '');
    // Format the CNIC
    let formattedCNIC = '';
    for (let i = 0; i < cnic.length; i++) {
        if (i === 5 || i === 12) {
            formattedCNIC += '-';
        }
        formattedCNIC += cnic[i];
    }
    // Update the input value
    input.value = formattedCNIC;
}

//confirmDelete
function ConfirmDelete()
{
    var x = confirm("Are you sure you want to delete?");
    if (x) {
        return true;
    }
    else {

        event.preventDefault();
        return false;
    }
}

function validateOnlyNumber(input) {
    input.value = input.value.replace(/\D/g, '');
}
