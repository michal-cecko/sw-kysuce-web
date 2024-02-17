<!-- Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2 (wrapper x2). Do not set height for flexible images (including "auto"). URL format: http://domain.com/?utm_source={{Campaign-Source}}&utm_medium=email&utm_content={{Ìmage-Name}}&utm_campaign={{Campaign-Name}} -->
<tr>
    <td align="center" valign="top"
        style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-top: 20px;"
        class="hero">
        <a target="_blank" style="text-decoration: none;" href="<?= $args['link'] ?? '#' ?>">
            <img border="0" vspace="0" hspace="0"
                 src="<?= $args['image'] ?>"
                 alt="Intro obrázok"
                 width="560"
                 style="width: 100%; max-width: 560px; color: #000000; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/>
        </a>
    </td>
</tr>