<script>
$(document).ready(function() {

  const fetch_user_data = (searchField) =>  // send ajax to check user information
    {   let verify_field = "."+searchField; // identify the class
      
       let input = document.querySelector(verify_field).value;
      if(input.trim()==="") return ;
      //triggering preload 
      $("#pageloader").fadeIn();
      
       $.ajax({  
        url:"ajax/beneficiaries_accounts.php?searchField",  
        method:"POST",  
        data:{input:input, searchField:searchField},
        dataType: 'json',
        success:(data)=>
        {  
            $('#userDataResponse').html(data.res); 
             $('#userDataResponse').css('display','block');
             $('#changeBeneficiary').css('display','none');
            $("#pageloader").fadeOut();
        },error:()=>{console.log("err"); }
        
       });  
    }

  $(document).on('keyup', '.account_name', ()=>
    {
      fetch_user_data("account_name")
    });

  $(document).on('click', '.loadUserData', ()=>   // load selected user details
    { 
      let btnId = event.target.id;
      // let input = document.querySelector('.loadUserData').value;
      let input = document.getElementById(btnId).value;
      
       $.ajax({  
        url:"ajax/beneficiaries_accounts.php?loadData",  
        method:"POST",  
        data:{input:input},
        dataType: 'json',
        success:(data)=>
        {  
        $('.account_number').val(data.account_number); $('.account_name').val(data.account_name); $('.bank_name').val(data.bank_name);
        $('.beneficiary_id').val(data.beneficiary_id);
        $('.account_number_load').val(data.account_number); $('.account_name_load').val(data.account_name); $('.bank_name_load').val(data.bank_name);
         // for backednd manupulation when existing user is selected
         $('.account_name').attr('disabled', 'disabled');$('.bank_name').attr('disabled', 'disabled');          
        $('.account_number').attr('disabled', 'disabled');
        $('.account-details').css('display','block');
         $('#userDataResponse').css('display','none');
         $('#changeBeneficiary').css('display','block');
         $('.submit-button').css('display','block');
        
        },error:()=>{console.log("err"); }
        
       });  
    });
  $(document).on('click', '#changeBeneficiary', ()=>
    {
      $('#userDataResponse').css('display','none'); $('.account-details').css('display','none');         
      $('#changeBeneficiary').css('display','none');
      $('.account_name').val(''); $('.account_number').val(''); $('.bank_name').val('');
      $('.account_name').removeAttr('disabled', '');$('.account_number').removeAttr('disabled', '');
      $('.bank_name').removeAttr('disabled', '');   $('.account_name').focus();
      $('.submit-button').css('display','none');
    });  
});
</script>


<script>
  const fetch_account_name = (accountNumber, bankCode) =>  
    {  
       if(accountNumber.length==10){ 
      //triggering preload 
      $("#pageloader").fadeIn();
       $.ajax({  
        url:"ajax/verify_account_details.php?check_account_name",  
        method:"POST",  
        data:{accountNumber:accountNumber, bank_code:bankCode},
        dataType: 'json',
        success:(data)=>
        {  
             //console.log('Data ', data);   
             if(data.res.account_name){
                $('.account_name').val(data.res.account_name); 
                $('#submit_beneficiary').css('display','block');  
                // $('#invalidAccount').stop(true, true).hide();   
                $('#invalidAccount').hide("slide");   
              }else{
                 $('.account_name').val(''); 
                $('#submit_beneficiary').css('display','none');
                // animateInvalidJquery();
                $('#invalidAccount').show("slide"); 
              }
            
            $("#pageloader").fadeOut();
        },error:()=>{console.log("err"); }
        
       });   
      }
 
    }
   // function handleSelectionChange(selectedValue, selectedText) {
  function handleSelectionChange() {
      // console.log('Value:', selectedValue);
      // console.log('Text:', selectedText);
      var selectElement = document.getElementById("bankSelect");
      var selectedValue = selectElement.value;
      var selectedIndex = selectElement.selectedIndex;
      var selectedOption = selectElement.options[selectedIndex];


      if (selectedValue) { 

        if(selectedIndex > 0) { // Assuming your first option is the placeholder
            // 3. Get the visible text
            var selectedText = selectedOption.text;
           // console.log("Selected Text (visible to user): " + selectedText);
            // 4. Get the index of the selected option
            //console.log("Selected Index: " + selectedIndex);

            var bankCode = selectedOption.getAttribute('data-bank-code');
            if (bankCode) {
              let accountNumber = document.querySelector('#accountNumber').value;
               if(accountNumber.length<10) return;

              fetch_account_name(accountNumber,bankCode);
         
            }
        } else {
            console.log("Please select a bank.");
        }

    }
  }


/*fetch(`ajax/verify_account_details.php?check_account_name`,{
    method: "POST",
    headers: {
        'Content-Type': 'application/json' // Or 'application/x-www-form-urlencoded'
    },
    body: JSON.stringify({ accountNumber: "8068211284", bank_code: bankCode }) // For JSON
    // body: new URLSearchParams({ accountNumber: "8068211284", bank_code: bankCode }) // For form-urlencoded
})
    .then(response => response.json())
    .then(details => {
        console.log('Details fetched for selected item:', details);
        // e.g., display these details in another part of your page
    })
    .catch(error => console.error('Error fetching details:', error));
  //console.log("Custom Data Attribute (Bank Code): " + bankCode);
    */
  function animateInvalidJquery() {
  $('#invalidAccount').fadeIn(1500, function() {
    $(this).fadeOut(1500, function() {
      animateInvalidJquery(); 
    });
  });
}
 </script>

<!-- Add new beneficiary section  -->
 <script>
   
  // Initialize document functions when ready
$(document).ready(function() {

  $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    }); 

  /*const fetch_bank_details = (accountNumber) =>  
    {   let verify_field = "#"+accountNumber; 
      
       let input = document.querySelector(verify_field).value;
       if(input.trim()==="") return ;
       if(input.length==10){ 
      //triggering preload 
      $("#pageloader").fadeIn();
       $.ajax({  
        url:"ajax/verify_account_details.php?verify_account",  
        method:"POST",  
        data:{accountNumber:input},
        dataType: 'json',
        success:(data)=>
        {  
             //console.log('Data ', data);             
            if (Array.isArray(data)) {
                if (data.length > 0 && typeof data[0] === 'object' && data[0] !== null) {
                // If it's an array of objects, assume 'id' for value, 'name' for text (adjust as needed)
                populateSelect(data, 'mySelect', 'id', 'name');
                } else {
                    // If it's a simple array (strings/numbers)
                    populateSelect(data, 'mySelect');
                }
            } else {
                console.warn('Expected an array from AJAX, but received:', data);
                document.getElementById('mySelect').innerHTML = '<option value="">Error: Invalid data</option>';
            }
            $("#pageloader").fadeOut();
        },error:()=>{console.log("err"); }
        
       });  
      }

    }*/

  /* const fetch_bank_details = (accountNumber) =>  
    {   let verify_field = "#"+accountNumber; 
      
       let input = document.querySelector(verify_field).value;
       if(input.trim()==="") return ;
       if(input.length==10){ 
      //triggering preload 
      $("#pageloader").fadeIn();
       $.ajax({  
        url:"ajax/verify_account_details.php?verify_account",  
        method:"POST",  
        data:{accountNumber:input},
        dataType: 'json',
        success:(data)=>
        {  
             console.log('Data ', data);             
            // Get the HTML element where you want to display the data
            const outputDiv = document.getElementById('verifiedAccountResponse'); // Assuming you have <div id="output"></div> in your HTML
            // Create an unordered list
            const ul = document.createElement('ul');
            // Loop through the array and create list items
            data.banks.forEach(item => {
                const li = document.createElement('li');
                li.textContent = 'code: '+item.code +' name: '+item.name + 'longcode: '+longcode;
                ul.appendChild(li);
            });
            // Append the list to the output div
            outputDiv.appendChild(ul);
            $("#pageloader").fadeOut();
        },error:()=>{console.log("err"); }
        
       });  
      }

    }*/

  $(document).on('keyup', '#accountNumber', ()=>
    {
      let accountNumber = document.querySelector('#accountNumber').value;
      if(accountNumber.length===10){
        $('#bank-select-section').show("slide");
       //fetch_bank_details("accountNumber")
      }else{
         $('#bank-select-section').hide("slide");
        $('#submit_beneficiary').hide("slide");
      }
    });

  // Function to populate the select field (Bank Suggestions here )
    /*function populateSelect(dataArray, selectId, valueKey = null, textKey = null) {
        const mySelect = document.getElementById(selectId);
        if (!mySelect) {
            console.error(`Select element with ID '${selectId}' not found.`);
            return;
        }

        mySelect.innerHTML = ''; // Clear existing options

        // Add a default "Please select" option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = (valueKey && textKey) ? `Select a ${textKey.toLowerCase().replace('name', 'item')}` : 'Please select...';
        // defaultOption.disabled = true; // Optional: Keep disabled if you don't want it selectable
        defaultOption.selected = true;
        mySelect.appendChild(defaultOption);

        dataArray.forEach(item => {
            const option = document.createElement('option');
            if (valueKey && textKey) {
                option.value = item[valueKey];
                option.textContent = item[textKey];
            } else {
                option.value = item;
                option.textContent = item;
            }
            mySelect.appendChild(option);
        });

        // --- NEW: Add the event listener after populating the select field ---
        mySelect.addEventListener('change', function() {
            const selectedValue = this.value; // 'this' refers to the select element
            const selectedText = this.options[this.selectedIndex].textContent;

            // Call your desired function here
            handleSelectionChange(selectedValue, selectedText);
        });
        // --- END NEW ---
    }*/

});

 </script>

