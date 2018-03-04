//
//  VisaOptionsTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/8/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Alamofire
import Braintree
import BraintreeDropIn
import PassKit

class VisaOptionsTVC: UITableViewController, BTAppSwitchDelegate, BTViewControllerPresentingDelegate, PKPaymentAuthorizationViewControllerDelegate {
    
    
    @IBOutlet weak var infoTV: UITextView!
    @IBOutlet weak var basicLbl: UILabel!
    @IBOutlet weak var basicInfoTV: UITextView!
    @IBOutlet weak var basicPayBtn: UIButton!
    @IBOutlet weak var interLbl: UILabel!
    @IBOutlet weak var interInfoTV: UITextView!
    @IBOutlet weak var interPayBtn: UIButton!
    @IBOutlet weak var advLbl: UILabel!
    @IBOutlet weak var advInfoTV: UITextView!
    @IBOutlet weak var advPayBtn: UIButton!
    
    var seekForTV = UITextView()
    var seekForAC = UIAlertController()
    var cardDetailsVC = CardDetailsTVC()
    var moreInfoTVC = MoreInfoTVC()
    
    var visa = Visa()
    var visaType = ""
    var visaPrice = 0
    var nonce = ""
    var seekFor = ""
    
    var expanded = Array<Int>()
    
    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
    
    var braintreeClient: BTAPIClient!
    var paymentMethodIndex = 0
    
    var clientToken = ""
    
    override func viewDidLoad() {
        super.viewDidLoad()

        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        self.navigationItem.title = visa.name
        
//        indicator.startAnimating()
        
        infoTV.text = visa.info
        
        basicInfoTV.text = visa.basicInfo
        basicInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        basicPayBtn.setTitle("$\(visa.basicPrice)", for: .normal)
        basicPayBtn.layer.cornerRadius = 10
     
        interInfoTV.text = visa.interInfo
        interInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        interPayBtn.setTitle("$\(visa.interPrice)", for: .normal)
        interPayBtn.layer.cornerRadius = 10
        
        advInfoTV.text = visa.advInfo
        advInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
        advPayBtn.setTitle("$\(visa.advPrice)", for: .normal)
        advPayBtn.layer.cornerRadius = 10
    
        fetchClientToken()
        
        configApplePay()
    }
    
    func configApplePay(){
        // Conditionally show Apple Pay button based on device availability
        PKPaymentAuthorizationViewController
            .canMakePayments(usingNetworks: [PKPaymentNetwork.visa,
                                             PKPaymentNetwork.masterCard,
                                             PKPaymentNetwork.quicPay])
        
    }
    
    func paymentRequest() -> PKPaymentRequest {
        let paymentRequest = PKPaymentRequest()
        paymentRequest.merchantIdentifier = "merchant.org.beyondmanagement.app"
        paymentRequest.supportedNetworks = [PKPaymentNetwork.amex, PKPaymentNetwork.visa, PKPaymentNetwork.masterCard]
        paymentRequest.merchantCapabilities = PKMerchantCapability.capability3DS
        paymentRequest.countryCode = "US"; // e.g. US
        paymentRequest.currencyCode = "USD"; // e.g. USD
        
        return paymentRequest
    }
    
    func payWithApplePay(_ itemName: String, _ amount: String) {
        let paymentRequest = self.paymentRequest()
        paymentRequest.paymentSummaryItems = [
            PKPaymentSummaryItem(label: itemName, amount: NSDecimalNumber(string: amount))
        ]
        // Example: Promote PKPaymentAuthorizationViewController to optional so that we can verify
        // that our paymentRequest is valid. Otherwise, an invalid paymentRequest would crash our app.
        if let vc = PKPaymentAuthorizationViewController(paymentRequest: paymentRequest)
            as PKPaymentAuthorizationViewController?
        {
            print("applePay present")
            
            self.definesPresentationContext = true
            vc.delegate = self
            self.present(vc, animated: true, completion: nil)
        } else {
            print("Error: Payment request is invalid.")
        }
    }
    
    func paymentAuthorizationViewControllerDidFinish(_ controller: PKPaymentAuthorizationViewController) {
        dismiss(animated: true, completion: nil)
    }
    
    func paymentAuthorizationViewController(_ controller: PKPaymentAuthorizationViewController,
                                            didAuthorizePayment payment: PKPayment, completion: @escaping (PKPaymentAuthorizationStatus) -> Void) {
        
        // Example: Tokenize the Apple Pay payment
        let applePayClient = BTApplePayClient(apiClient: braintreeClient!)
        applePayClient.tokenizeApplePay(payment) {
            (tokenizedApplePayPayment, error) in
            guard let tokenizedApplePayPayment = tokenizedApplePayPayment else {
                // Tokenization failed. Check `error` for the cause of the failure.
                
                // Indicate failure via completion callback.
                completion(PKPaymentAuthorizationStatus.failure)
                
                return
            }
            
            // Received a tokenized Apple Pay payment from Braintree.
            // If applicable, address information is accessible in `payment`.
            
            // Send the nonce to your server for processing.
            print("nonce = \(tokenizedApplePayPayment.nonce)")
            self.postNonceToServerVault(tokenizedApplePayPayment.nonce, self.visaPrice, self.seekFor)
            
            // Then indicate success or failure via the completion callback, e.g.
            completion(PKPaymentAuthorizationStatus.success)
        }
    }

    
    @IBAction func basicPayBtnOnClick(_ sender: UIButton) {
        visaType = "basic"
        visaPrice = visa.basicPrice
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visaPrice)
    }
    
    @IBAction func interPayBtnOnClick(_ sender: UIButton) {
        visaType = "intermediate"
        visaPrice = visa.interPrice
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visaPrice)
    }
    
    @IBAction func advPayBtnOnClick(_ sender: UIButton) {
        visaType = "advanced"
        visaPrice = visa.advPrice
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visaPrice)
    }
    
//    func showAlert() {
//
//        self.seekForAC = UIAlertController(title: "What are you seeking for?", message: "", preferredStyle: .alert)
//        self.seekForAC.addAction(UIAlertAction(title: "Cancel", style: .destructive, handler: nil))
//
//        let saveAction = UIAlertAction(title: "Next", style: .default, handler: { (action) -> Void in
//
//            self.showDropIn(clientTokenOrTokenizationKey: self.clientToken, amount: self.visaPrice)
//        })
//
//        saveAction.isEnabled = false
//
//        seekForAC.view.addObserver(self, forKeyPath: "bounds", options: NSKeyValueObservingOptions.new, context: nil)
//
//        NotificationCenter.default.addObserver(forName: NSNotification.Name.UITextViewTextDidChange, object: seekForTV, queue: OperationQueue.main) { (notification) in
//            saveAction.isEnabled = self.seekForTV.text != ""
//        }
//
//        seekForTV.addBorder(view: seekForTV, stroke: UIColor.black, fill: UIColor.white, radius: 10, width: 2)
//        seekForTV.frame = CGRect(x: 15, y: 50, width: 240, height: 150)
//        seekForTV.backgroundColor = UIColor.white
//        seekForAC.view.addSubview(self.seekForTV)
//
//        seekForAC.addAction(saveAction)
//
//        self.present(seekForAC, animated: true, completion: nil)
//
//    }
//
//    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
//        if keyPath == "bounds"{
//            if let rect = (change?[NSKeyValueChangeKey.newKey] as? NSValue)?.cgRectValue{
//                let margin:CGFloat = 8.0
//                seekForTV.frame = CGRect(x: rect.origin.x + margin, y: rect.origin.y + margin, width: rect.width - 2*margin, height: rect.height / 2)
//            }
//        }
//    }
    
    func alertNextAction(_ sender: UIAlertAction){
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: visaPrice)
    }
    
    // Payment
    
    func showDropIn(clientTokenOrTokenizationKey: String, amount: Int) {
        
        indicator.startAnimating()
        
        let request =  BTDropInRequest()
        request.amount = "\(amount)"
        request.applePayDisabled = false
        
        if clientTokenOrTokenizationKey.isEmpty {
            indicator.stopAnimating()
            return
        }
        
        let dropIn = BTDropInController(authorization: clientTokenOrTokenizationKey, request: request){
            (controller, result, error) in
            
            self.indicator.stopAnimating()
            
            if (error != nil) {
                print("ERROR")
            } else if (result?.isCancelled == true) {
                print("CANCELLED")
            } else if let result = result {
                // Use the BTDropInResult properties to update your UI
                // result.paymentOptionType
                // result.paymentMethod
                // result.paymentIcon
                // result.paymentDescription
                
                print(result.paymentMethod?.nonce ?? "")
                           
                self.definesPresentationContext = false
                self.nonce = (result.paymentMethod?.nonce ?? "")
                self.visaPrice = amount
                
                if result.paymentOptionType == BTUIKPaymentOptionType.applePay{
                    print("pay with apple pay")
                    self.paymentMethodIndex = 2
                    self.moreInfoTVC =  self.storyboard?.instantiateViewController(withIdentifier: "MoreInfoTVC") as! MoreInfoTVC
                    self.moreInfoTVC.moreInfoDelegate = self
                    
                    self.present(self.moreInfoTVC, animated: true, completion: nil)
                
                }
                else if result.paymentOptionType == BTUIKPaymentOptionType.visa{
                    print("pay with visa")
                    self.paymentMethodIndex = 1
                    self.cardDetailsVC =  self.storyboard?.instantiateViewController(withIdentifier: "CardDetailsTVC") as! CardDetailsTVC
                    self.cardDetailsVC.cardDetailsDelegate = self
                    
                    self.present(self.cardDetailsVC, animated: true, completion: nil)
                }
                else{
//                    self.postNonceToServerVault((result.paymentMethod?.nonce ?? ""), amount)
                    self.paymentMethodIndex = 0
                    
                    self.moreInfoTVC =  self.storyboard?.instantiateViewController(withIdentifier: "MoreInfoTVC") as! MoreInfoTVC
                    self.moreInfoTVC.moreInfoDelegate = self
                    
                    self.present(self.moreInfoTVC, animated: true, completion: nil)
                }
                
            }
            controller.dismiss(animated: true, completion: nil)
        }
        
        self.present(dropIn!, animated: true, completion: nil)
    }
    
    func postNonceToServerVault(_ paymentMethodNonce: String, _ amount: Int, _ seekFor: String) {
        
        indicator.startAnimating()
        //let dataCollector = BTDataCollector(environment: .Sandbox)
        //let deviceData = dataCollector.collectCardFraudData()
        
        let deviceData = PPDataCollector.collectPayPalDeviceData()
        let customerId = UserDefaults().string(forKey: "customerId")!
        print("postNonceToServerVault deviceData: \(deviceData)")
        
        let params: Parameters = [
            "payment_method_nonce": paymentMethodNonce,
            "customerId": customerId,
            "amount": "\(amount)",
            "visaName": visa.name,
            "visaType": visaType,
            "seekFor": seekFor
        ]
        
        print(params)
        
        Alamofire.request(Urls.PAY_NONCE_VAULT, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let json = result as! NSDictionary
                
                if let success = json.value(forKey: "paySuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Thank you, Your request have been submitted.\n You will receive a notification to complete the required documents.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "payError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                else if let success = json.value(forKey: "methodSuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Your payment method have been added.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "methodError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                
            }
            
            self.indicator.stopAnimating()
        }
        
    }
    
    func postVisaNonceToServerVault(_ paymentMethodNonce: String, _ amount: Int, cardHolder: String, cvv: String, billingAddress: String, seekFor: String) {
        
        indicator.startAnimating()
        //let dataCollector = BTDataCollector(environment: .Sandbox)
        //let deviceData = dataCollector.collectCardFraudData()
        
        let deviceData = PPDataCollector.collectPayPalDeviceData()
        let customerId = UserDefaults().string(forKey: "customerId")!
        print("postNonceToServerVault deviceData: \(deviceData)")
        
        let params: Parameters = [
            "payment_method_nonce": paymentMethodNonce,
            "customerId": customerId,
            "amount": "\(amount)",
            "visaName": visa.name,
            "visaType": visaType,
            "cardHolder": cardHolder,
            "cvv": cvv,
            "billingAdd": billingAddress,
            "seekFor": seekFor
        ]
        
        print(params)
        
        Alamofire.request(Urls.PAY_NONCE_VAULT, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let json = result as! NSDictionary
                
                if let success = json.value(forKey: "paySuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Thank you, Your request have been submitted.\n You will receive a notification to complete the required documents.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "payError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                else if let success = json.value(forKey: "methodSuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Your payment method have been added.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "methodError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                
            }
            
            self.indicator.stopAnimating()
        }
        
    }
    
    func fetchClientToken(){
        
        let parameters: Parameters = [
            "customerId": UserDefaults().string(forKey: "customerId")!
        ]
        
        print(parameters)
        indicator.startAnimating()
        Alamofire.request(Urls.CLIENT_TOKEN, method: .post, parameters: parameters)
            .responseJSON{
                
                response in
                
                print(response)
                
                if let result = response.result.value {
                    
                    let json = result as! NSDictionary
                    self.clientToken = json.value(forKey: "token") as! String
                    self.braintreeClient = BTAPIClient(authorization: self.clientToken)
                }
                self.indicator.stopAnimating()
        }
        
    }
    
    // MARK: - BTViewControllerPresentingDelegate
    func paymentDriver(_ driver: Any, requestsDismissalOf viewController: UIViewController) {
        dismiss(animated: true, completion: nil)
    }
    
    func paymentDriver(_ driver: Any, requestsPresentationOf viewController: UIViewController) {
        present(viewController, animated: true, completion: nil)
    }
    
    // MARK: - BTAppSwitchDelegate
    func appSwitcherWillPerformAppSwitch(_ appSwitcher: Any) {
        
    }
    
    func appSwitcher(_ appSwitcher: Any, didPerformSwitchTo target: BTAppSwitchTarget) {
        
    }
    
    func appSwitcherWillProcessPaymentInfo(_ appSwitcher: Any) {
        
    }
    
    
    // MARK: - Table view data source

    override func numberOfSections(in tableView: UITableView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }

    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return 4
    }

    
    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        
        switch indexPath.row {
    
        case 1:
            if expanded.contains(where: {$0 == indexPath.row}){
                basicInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                basicLbl.text = "↓ Basic"
                return 150
            }
            else {
                basicInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                basicLbl.text = "→ Basic"
                return 46
            }
        case 2:
            if expanded.contains(where: {$0 == indexPath.row}){
                interInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                interLbl.text = "↓ Intermediate"
                return 150
            }
            else {
                interInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                interLbl.text = "→ Intermediate"
                return 46
            }
        case 3:
            if expanded.contains(where: {$0 == indexPath.row}){
                advInfoTV.frame = CGRect(x: 8, y: 40, width: self.view.frame.width - 16, height: 100)
                advLbl.text = "↓ Advanced"
                return 150
            }
            else {
                advInfoTV.frame = CGRect(x: 8, y: 46, width: self.view.frame.width - 16, height: 0)
                advLbl.text = "→ Advanced"
                return 46
            }
        default:
            return 150
        }
        
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
    
        if indexPath.row != 0 {
           
            if expanded.contains(where: {$0 == indexPath.row}){
                // it exists
                expanded.remove(at: expanded.index(of: indexPath.row)!)
            }
            else{
                //item could not be found
                expanded.append(indexPath.row)
            }
            
            self.tableView.reloadData()
            print(expanded)
            
        }
    }
   
}

extension VisaOptionsTVC: MoreInfoDelegate, CardDetailsDelegate{    
    
    func info(seekFor: String) {
        
        self.seekFor = seekFor
        
        moreInfoTVC.dismiss(animated: true, completion: nil)
        
        switch paymentMethodIndex {
        case 0:
            self.postNonceToServerVault(nonce, visaPrice, seekFor)
            break
        case 2:
            self.payWithApplePay(self.visaType, "\(visaPrice)")
            break
        default:
            break
        }
        
    }
    
    func cardDetailsWithSeek(name: String, cvv: String, strAddress: String, city: String, state: String, zipCode: String, country: String, seekFor: String) {
        
        cardDetailsVC.dismiss(animated: true, completion: nil)
        let address = strAddress + ", " + city + ", " + state + ", " + zipCode + ", " + country
        self.postVisaNonceToServerVault(nonce, visaPrice, cardHolder: name, cvv: cvv, billingAddress: address, seekFor: seekFor)
        
    }
    
    func cardDetails(name: String, cvv: String, strAddress: String, city: String, state: String, zipCode: String, country: String) {
        
        
    }
    
}


