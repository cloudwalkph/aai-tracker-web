@extends('layouts.app')

@section('page-styles')
    <style>
        ul {
            margin: 0;
            padding: 0;
        }

        ul li {
            list-style: none;
        }

        .circle {
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }

        .connected {
            background-color: #29bb29;
        }

        .not-connected {
            background-color: #af3742;
        }
    </style>
@endsection

@section('page-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/cipher-core-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/sha256-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/hmac-sha256.js"></script>

    <script>
        (function() {
            var options = {
                clientId : 'someId',
                endpoint: 'a3txyp5250854n.iot.ap-northeast-2.amazonaws.com',
                accessKey: 'AKIAJBJ7NOOWO6MB4OTQ',
                secretKey: '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',
                regionName: 'ap-northeast-2'
            };

            var requestUrl = computeUrl();

            // Create a client instance
            var client = new Paho.MQTT.Client(requestUrl, options.clientId);

            // set callback handlers
            client.onConnectionLost = onConnectionLost;
            client.onMessageArrived = onMessageArrived;

            // connect the client
            client.connect({onSuccess:onConnect});


            // called when the client connects
            function onConnect() {
                // Once a connection has been made, make a subscription and send a message.
//                client.subscribe("$aws/things/raspbi-1/shadow/update/accepted");
                client.subscribe("raspi-1");
            }

            // called when the client loses its connection
            function onConnectionLost(responseObject) {
                if (responseObject.errorCode !== 0) {
                    console.log("onConnectionLost:"+responseObject.errorMessage);
                }
            }

            function sign(key, msg){
                var hash = CryptoJS.HmacSHA256(msg, key);
                return hash.toString(CryptoJS.enc.Hex);
            }

            function sha256(msg) {
                var hash = CryptoJS.SHA256(msg);
                return hash.toString(CryptoJS.enc.Hex);
            }

            function getSignatureKey(key, dateStamp, regionName, serviceName) {
                var kDate = CryptoJS.HmacSHA256(dateStamp, 'AWS4' + key);
                var kRegion = CryptoJS.HmacSHA256(regionName, kDate);
                var kService = CryptoJS.HmacSHA256(serviceName, kRegion);
                var kSigning = CryptoJS.HmacSHA256('aws4_request', kService);

                return kSigning;
            }

            function computeUrl() {
                // must use utc time
                var time = moment.utc();
                var dateStamp = time.format('YYYYMMDD');
                var amzdate = dateStamp + 'T' + time.format('HHmmss') + 'Z';
                var service = 'iotdevicegateway';
                var region = options.regionName;
                var secretKey = options.secretKey;
                var accessKey = options.accessKey;
                var algorithm = 'AWS4-HMAC-SHA256';
                var method = 'GET';
                var canonicalUri = '/mqtt';
                var host = options.endpoint;

                var credentialScope = dateStamp + '/' + region + '/' + service + '/' + 'aws4_request';
                var canonicalQuerystring = 'X-Amz-Algorithm=AWS4-HMAC-SHA256';
                canonicalQuerystring += '&X-Amz-Credential=' + encodeURIComponent(accessKey + '/' + credentialScope);
                canonicalQuerystring += '&X-Amz-Date=' + amzdate;
                canonicalQuerystring += '&X-Amz-Expires=86400';
                canonicalQuerystring += '&X-Amz-SignedHeaders=host';

                var canonicalHeaders = 'host:' + host + '\n';
                var payloadHash = sha256('');
                var canonicalRequest = method + '\n' + canonicalUri + '\n' + canonicalQuerystring + '\n' + canonicalHeaders + '\nhost\n' + payloadHash;
                console.log('canonicalRequest ' + canonicalRequest);

                var stringToSign = algorithm + '\n' +  amzdate + '\n' +  credentialScope + '\n' +  sha256(canonicalRequest);
                var signingKey = getSignatureKey(secretKey, dateStamp, region, service);
                console.log('stringToSign-------');
                console.log(stringToSign);
                console.log('------------------');
                console.log('signingKey ' + signingKey);
                var signature = sign(signingKey, stringToSign);

                canonicalQuerystring += '&X-Amz-Signature=' + signature;
                var requestUrl = 'wss://' + host + canonicalUri + '?' + canonicalQuerystring;
                return requestUrl;
            }

            // called when a message arrives
            function onMessageArrived(message) {
                console.log("onMessageArrived:" + message.payloadString);

                isConnected(message.payloadString);
                isCameraConnected(message.payloadString);
            }

            function isConnected(payload) {
                if (payload === 'status-connected') {
                    var device = document.querySelector('#raspi-1');

                    device.classList.remove('not-connected');
                    device.classList.add('connected');
                }
            }

            function isCameraConnected(payload) {
                if (payload === 'camera-connected') {
                    var device = document.querySelector('#raspi-1-camera');

                    device.classList.remove('not-connected');
                    device.classList.add('connected');
                }
            }
        }());
    </script>
@endsection

@section('content')
<div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <h1>Devices</h1>
                    <table class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Device Name</th>
                                <th>Camera</th>
                                <th>Streaming</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="circle not-connected" id="raspi-1"></div>
                                </td>
                                <td>
                                    Raspi-1
                                </td>
                                <td>
                                    <div class="circle not-connected" id="raspi-1-camera"></div>
                                </td>
                                <td>
                                    <div class="circle not-connected" id="raspi-1-streaming"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
