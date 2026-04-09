* {
    box-sizing: border-box;
}

:root {
    --idm-orange: #f35b21;
    --idm-orange-dark: #e6461a;
    --idm-text: #131313;
    --idm-muted: #6f6f6f;
}

#printArea {
    display: flex;
    justify-content: center;
}

.idm-card-sheet {
    width: 12cm;
    display: flex;
    gap: 0.6cm;
    justify-content: center;
    flex-wrap: wrap;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
}

.idm-card {
    width: 5.5cm;
    height: 8.5cm;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #dadada;
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
    position: relative;
    overflow: hidden;
}

.idm-front {
    display: flex;
}

.idm-side-ribbon {
    width: 28px;
    background: linear-gradient(180deg, var(--idm-orange) 0%, var(--idm-orange-dark) 100%);
    display: flex;
    justify-content: center;
    align-items: center;
}

.idm-side-ribbon span {
    color: #fff;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 1.1px;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    text-transform: uppercase;
    text-align: center;
}

.idm-content {
    flex: 1;
    padding: 12px 10px 10px;
    text-align: center;
    color: var(--idm-text);
}

.idm-brand {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
    margin-bottom: 10px;
    margin-top: 10px;
}

.idm-brand-mark {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: #fff;
    font-size: 10.5px;
    font-weight: 700;
}

.idm-brand-mark img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.idm-brand-copy h5 {
    margin: 0;
    font-size: 8px;
    line-height: 1.1;
    text-transform: uppercase;
    font-weight: 800;
}

.idm-brand-copy p {
    margin: 5px 0 0;
    font-size: 6px;
    color: var(--idm-muted);
    font-weight: 700;
}

.idm-avatar-wrap {
    width: 64px;
    height: 64px;
    margin: 8px auto 8px;
    border-radius: 50%;
    border: 2px solid var(--idm-orange);
    overflow: hidden;
}

.idm-avatar-wrap img {
    width: 100%;
    height: 100%;
}

.idm-name {
    margin: 0;
    margin-top: 15px;
    font-size: 12.2px;
    text-transform: uppercase;
    font-weight: 800;
    line-height: 1.2;
}

.idm-role {
    margin: 2px 0 4px;
    font-size: 7.5px;
    color: #5b5b5b;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.idm-blood {
    margin: 0 0 9px;
    font-size: 8.1px;
    color: #5b5b5b;
    font-weight: 700;
}

.idm-contact {
    font-size: 6.9px;
    line-height: 1.45;
    color: #343434;
    min-height: 48px;
    text-align: left;
}

.idm-contact div {
    display: flex;
    justify-content: space-between;
    gap: 6px;
    border-bottom: 1px solid #ececec;
    padding: 4.5px 0;
}

.idm-contact span {
    color: #777;
    letter-spacing: .2px;
}

.idm-contact strong {
    font-weight: 700;
    color: #222;
    text-align: right;
    max-width: 92px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.idm-qrcode {
    width: 60px;
    height: 60px;
    margin: 9px auto 0;
    padding: 6px;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #ececec;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.idm-qrcode img {
    width: 100%;
    height: 100%;
    display: block;
}

.idm-qrcode span {
    font-size: 9px;
    font-weight: 700;
    color: #444;
    letter-spacing: .6px;
    text-align: center;
    word-break: break-word;
}

.idm-back-content {
    padding: 14px 12px 0;
    color: #2b2b2b;
}

.idm-back-content h6 {
    margin: 0 0 6px;
    font-size: 10.4px;
    font-weight: 700;
}

.idm-back-content p {
    margin: 0;
    font-size: 7.2px;
    line-height: 1.5;
    color: #696969;
}

.idm-dates {
    margin-top: 8px;
    font-size: 10.4px;
    line-height: 1.45;
}

.idm-company {
    margin-top: 12px;
    font-size: 7.1px;
    color: #4f4f4f;
    line-height: 1.35;
}

.idm-company div {
    margin-top: 2px;
    word-break: break-word;
}

.idm-sign {
    margin-top: 20px;
}

.idm-sign span {
    display: block;
    font-size: 8.4px;
    color: #757575;
}

.idm-sign small {
    font-size: 8.4px;
    color: #222;
    letter-spacing: .2px;
}

.idm-geo-shape {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 54px;
    overflow: hidden;
}

.idm-geo-shape .s1,
.idm-geo-shape .s2,
.idm-geo-shape .s3 {
    position: absolute;
    bottom: 0;
    width: 0;
    height: 0;
}

.idm-geo-shape .s1 {
    left: 0;
    border-bottom: 54px solid #ff6a2d;
    border-right: 5.5cm solid transparent;
}

.idm-geo-shape .s2 {
    right: 0;
    border-bottom: 40px solid #f04a1d;
    border-left: 4.2cm solid transparent;
}

.idm-geo-shape .s3 {
    right: 0;
    border-bottom: 26px solid #dd3a16;
    border-left: 2.8cm solid transparent;
}

