//
//  RequirementTVCell.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 1/6/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import UIKit

class RequirementTVCell: UITableViewCell {

    @IBOutlet weak var whiteV: UIView!
    @IBOutlet weak var docIV: UIImageView!
    @IBOutlet weak var docName: UILabel!
    @IBOutlet weak var statusLbl: UILabel!
    @IBOutlet weak var uploadBtn: UIButton!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        contentView.frame = CGRect(x: 16, y: 5, width: contentView.frame.width - 32, height: 150)
        whiteV.layer.cornerRadius = 15
        whiteV.layer.masksToBounds = true
        
        docIV.contentMode = .scaleAspectFit
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

}
