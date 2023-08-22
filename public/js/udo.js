var token;

const init = {
    method: 'GET', cache: 'no-store', credentials: 'include',
    headers: {'X-CSRF-TOKEN': token, 'Accept': 'text/html,application/json'}
};

$(function() {
    token = $("meta[name='csrf-token']").attr('content');

    $('#aes-key-gen').on('click', generateKey);
    $('#generate-keys-btn').on('click', generateRSAKeys)
});

function generateKey() {
    uFetch($('#aeskey-link').html(), init, {
        success: function(data) {
            $('#notification').html(data.view);
            $('#aeskey').val(data.aesKey);
        }
    });
    return false;
}

function generateRSAKeys() {
    const formData = new FormData();
    formData.append('name', $('#rsa-name').val());
    const init = {
        method: 'POST', credentials: 'include', body: formData,
        headers: {'X-CSRF-TOKEN': token, 'Accept': 'text/html,application/json'}
    };

    uFetch($(this).parents('form').prop('action'), init, {
        success: function(data) {
            $('#notification').html(data.view);
            $('#rsapubkey').val(data.pub_name);
            $('#rsaprikey').val(data.pri_name);
            $('#publink').prop('href', data.pub_url);
            $('#prilink').prop('href', data.pri_url);
        }
    });
    return false;
}

function uFetch(url, init, callbacks) {
    fetch(url, init).then(response => {
        if(!response.ok) throw new Error('invalid server response: ' + response.statusText);
        if(response.headers.get('content-type')?.includes("text/html")) return response.text();
        if(response.headers.get('content-type')?.includes("application/json")) return response.json();
    }).then(data => {
        if(callbacks.success) callbacks.success(data);
    }).catch(error => {
        console.log(error.message);
        if(callbacks.fail) callbacks.fail();
    }).finally(() => {
        if(callbacks.always) callbacks.always();
    });
}