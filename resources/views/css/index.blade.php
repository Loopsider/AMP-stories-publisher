body{
    background-color:black;
    color:white;
}

.btn__anchor{
    padding-top:3px;
    padding-bottom:3px;
    padding-left:10px;
}

#menu {
    background:gray;
    width: 100%;
    color: white;
    padding: 0;
    margin: 0
}

#menu .close {
    cursor: pointer;
    font-size: 25px;
    position: absolute;
    top: 30px;
    right: 30px
}

.slide .caption {
    bottom: 80px;
    padding-left: 55px
}

.video .caption {
    bottom: 20px;
    padding-left: 20px
}

.slide .caption h2,
.video .caption h2 {
    color: #fff;
    font-family: rationalbook_bold;
    font-size: 52px;
    line-height: 54px;
    margin-top: 5px;
    text-shadow: 1px 1px 5px #262626;
}

.video .caption h2 {
    font-size: 2.5vw;
    line-height: 2.74vw;
    margin-top: 6px
}

.video .caption h2.small {
    font-size: 1.43vw;
    line-height: 1.55vw;
    margin-top: 0
}

main header article.slide {
    margin: 0;
    position: relative
}

main header.xs-hide article.slide:first-of-type {
    border-left: solid 30px transparent
}

::-webkit-scrollbar{width: 0;background: none;}

main header.xs-hide article.slide:last-of-type {
    border-right: solid 30px transparent
}

main header.xs-hide.large-screen article.slide {
    width: 819px;
    height: 819px;
    border: 12px solid transparent
}

main header.xs-hide.small-medium-screen article.slide {
    width: 615px;
    height: 615px;
    border: 12px solid transparent
}


.story-carousel {
    margin-left: 10px;
}

@media only screen and (min-width: 800px) {
    .poss-amp-carousel__lg {
        display: block;
    }
    .poss-amp-carousel__sm {
        display: none;
    }
}

@media only screen and (max-width:800px) {
    .poss-amp-carousel__lg {
        display: none;
    }
    .poss-amp-carousel__sm {
        display: block;
    }
}
