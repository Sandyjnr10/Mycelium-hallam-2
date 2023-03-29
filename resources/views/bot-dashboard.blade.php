<?php

use App\Http\Requests\AuthenticateTwitterAppForAccount;

$twitterAPI = new AuthenticateTwitterAppForAccount();
$twitterPreAuthData = $twitterAPI->getDataForLogin(getenv('CALLBACK_URL'));
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <h1>Create Job button</h1>

    <input type="checkbox" id="twitter" name="twitter" value="twitter">
    <label for="twitter">Twitter</label><br>

    <input type="checkbox" id="mastodon" name="mastodon" value="mastodon">
    <label for="mastodon">Mastodon</label><br>

    <input type="text" id="status-body" name="statusbody" value="" placeholder="message body">

    <input id="button-create-socials-post" type="button" value="CreateJob" onclick="createSocialsJobs();" /></br></br>

    <input type="text" id="direct-message-recipient" name="directmessagerecipient" value="" placeholder="recipient"></br>
    <input type="text" id="direct-message-body" name="directmessagebody" value="" placeholder="message body">
    <input id="button-create-direct-message" type="button" value="CreateJob" onclick="createDirectMessageJob();" /></br></br>

    <input type="text" id="username-body" name="usernamebody" value="" placeholder="username">
    <input id="button-get-userID" type="button" value="CreateJob" onclick="createGetUserIDByUsernameJob();" /></br>

    <div class="section-action-container">
        <a href="<?php echo $twitterPreAuthData['twitter_login_url']; ?>">
            <div class="tw-button-container">
                Authenticate app for Twitter Account
            </div>
        </a>
    </div>
</body>

<script>
    function createSocialsJobs() {
        var twitterChecked = document.getElementById('twitter').checked;
        var mastodonChecked = document.getElementById('mastodon').checked;
        var statusMessage = document.getElementById('status-body').value;

        if (!twitterChecked && !mastodonChecked) {
            console.log('No sites selected');
            return;
        }

        if (statusMessage == '') {
            console.log('message body cannot be empty');
            return;
        }

        if (twitterChecked) {
            let url = window.location.origin + '/api/twitter/post-status';
            MakeRequest(url, statusMessage);
        }

        if (mastodonChecked) {
            let url = window.location.origin + '/api/mastodon/post-status';
            MakeRequest(url, statusMessage);
        }
    }

    function createDirectMessageJob() {
        var recipientID = document.getElementById('direct-message-recipient').value;
        var messageBody = document.getElementById('direct-message-body').value;

        if (recipientID == "") {
            console.log("No recipient ID given");
            return;
        }

        if (messageBody == "") {
            console.log("Message body cannot be empty");
            return;
        }

        if (messageBody.length > 140) {
            console.log("Message body is too long, 140 characters max");
            return;
        }

        try {
            parseInt(recipientID);
        } catch {
            console.log("Recipient ID must be an integer");
            return;
        }


        let content = [recipientID, messageBody]

        let url = window.location.origin + "/api/twitter/send-dm";
        MakeRequest(url, content);
    }

    function createGetUserIDByUsernameJob() {
        var username = document.getElementById('username-body').value;

        if (username == "") {
            console.log("username cannot be empty");
            return;
        }

        let url = window.location.origin + '/api/twitter/getIDFromUsername';
        MakeRequest(url, username);
    }

    async function MakeRequest(url, content) {
        var myHeaders = new Headers();
        myHeaders.append("Body", "");
        myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

        var urlencoded = new URLSearchParams();
        urlencoded.append("content", content);

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: urlencoded,
            redirect: 'follow'
        };

        fetch(url, requestOptions)
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.log('error', error));
    }
</script>

</html>