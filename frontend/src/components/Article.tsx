type CardProps = {
    title: string;
    navigateUrl: string;
    description?: string;
    imageUrl?: string;
    source: {
        name: string;
        url: string;
    }
};

const Article: React.FC<CardProps> = ({ title, description, imageUrl, navigateUrl }) => {
    return (
        <div 
            className="border-2 py-2 px-3 border-gray-300 hover:opacity-60 duration-300"
        >
            {imageUrl && <img src={imageUrl} alt={title} />}

            <a href={navigateUrl}>
                {title}
            </a>

            {description && <p>{description}</p>}
        </div>
    );
};

export default Article;