//
//  CardDetailsVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 2/5/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit

class CardDetailsVC: UIViewController, UITextViewDelegate {

    @IBOutlet weak var cardHolderNameTF: UITextField!
    @IBOutlet weak var billingAddressTV: UITextView!
    @IBOutlet weak var seekForTV: UITextView!
    
    @IBOutlet weak var billingAddLbl: UILabel!
    @IBOutlet weak var cardHolderLbl: UILabel!
    @IBOutlet weak var seekForLbl: UILabel!
    var amount = 0
    var nonce = ""
    var cardDetailsDelegate: CardDetailsDelegate?
    
    var paymentMethodIndex = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        billingAddressTV.addBorder(view: billingAddressTV, stroke: UIColor.greyMidColor(), fill: UIColor.clear, radius: 10, width: 2)
        seekForTV.addBorder(view: seekForTV, stroke: UIColor.greyMidColor(), fill: UIColor.clear, radius: 10, width: 2)
        seekForTV.delegate = self
        billingAddressTV.delegate = self
        
        if paymentMethodIndex != 1{
            billingAddressTV.isHidden = true
            cardHolderNameTF.isHidden = true
            cardHolderLbl.isHidden = true
            billingAddLbl.isHidden = true
            
            billingAddressTV.frame = CGRect.zero
            cardHolderNameTF.frame = CGRect.zero
            cardHolderLbl.frame = CGRect.zero
            billingAddLbl.frame = CGRect.zero
            
            seekForLbl.frame = CGRect(x: 16, y: 85, width: self.view.frame.width - 32, height: 21)
            seekForTV.frame = CGRect(x: 16, y: seekForLbl.frame.maxY, width: self.view.frame.width - 32, height: 85)
        }
    }

    @IBAction func cancelBtnOnClick(_ sender: Any) {
        dismiss(animated: true, completion: nil)
    }
    
    @IBAction func nextBtnOnClick(_ sender: Any) {
        
        if (cardHolderNameTF.text?.isEmpty)! && paymentMethodIndex == 1{
            let alert = UIAlertController(title: "Error", message: "Please enter Card Holder Name", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing card holder name")
            return
        }
        if (billingAddressTV.text.isEmpty || billingAddressTV.text == "Billing Address") && paymentMethodIndex == 1{
            let alert = UIAlertController(title: "Error", message: "Please enter the billing address", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing billing address")
            return
        }
        if seekForTV.text.isEmpty || seekForTV.text == "What are you seeking for?"{
            let alert = UIAlertController(title: "Error", message: "Please say what are you seeking for", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try again", style: UIAlertActionStyle.default, handler: nil))
            self.present(alert, animated: true, completion: nil)
            print("missing seeking for")
            return
        }
//        cardDetailsDelegate?.cardDetails(name: cardHolderNameTF.text!, address: billingAddressTV.text, amount: amount, nonce: nonce, seekFor: seekForTV.text)
    }

    func textViewDidBeginEditing(_ textView: UITextView) {
        if textView == billingAddressTV{
            if textView.text == "Billing Address"{
                textView.text = ""
                textView.textColor = UIColor.black
            }
        }
        if textView == seekForTV{
            if textView.text == "What are you seeking for?"{
                textView.text = ""
                textView.textColor = UIColor.black
            }
        }
        
    }
    
    func textViewDidEndEditing(_ textView: UITextView) {
        
        if textView == billingAddressTV{
            if textView.text == ""{
                textView.text = "Billing Address"
                textView.textColor = UIColor.greyMidColor()
            }
        }
        if textView == seekForTV{
            if textView.text == ""{
                textView.text = "What are you seeking for?"
                textView.textColor = UIColor.greyMidColor()
            }
        }
    }
}

